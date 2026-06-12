<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Models\AiEnrichment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AIService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openai.key', '');
        $this->model = config('services.openai.model', 'gpt-4o-mini');
        $this->baseUrl = config('services.openai.base_url', 'https://api.openai.com/v1');
    }

    /**
     * Discover schools/institutions in a given location
     */
    public function discoverSchools(string $city, string $state = '', string $type = 'school'): array
    {
        $prompt = "List 10 {$type}s in {$city}" . ($state ? ", {$state}" : "") . ", India. 
        For each institution, provide the following in JSON format:
        - name: Institution name
        - contact_person: Principal/Director name (if known, otherwise 'N/A')
        - email: Email address (if available, otherwise 'N/A')
        - phone: Phone number (if available, otherwise 'N/A')
        - website: Website URL (if available, otherwise 'N/A')
        - address: Full address
        - type: {$type}
        - board: Education board (CBSE/ICSE/State Board/etc)
        - student_count: Approximate student count (number only, or 0 if unknown)
        - medium: Medium of instruction

        Return ONLY a valid JSON array. No extra text.";

        try {
            $response = $this->chat($prompt);
            $data = json_decode($response, true);
            return is_array($data) ? $data : [];
        } catch (\Exception $e) {
            Log::error('AI School Discovery Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Enrich a lead with AI-generated data
     */
    public function enrichLead(Lead $lead): AiEnrichment
    {
        $enrichment = AiEnrichment::create([
            'lead_id' => $lead->id,
            'provider' => 'openai',
            'query' => "Enrich: {$lead->school_name}, {$lead->city}",
            'status' => 'processing',
        ]);

        try {
            $prompt = "I need detailed information about this educational institution:
            Name: {$lead->school_name}
            City: {$lead->city}
            State: {$lead->state}
            Type: {$lead->type}
            
            Please provide in JSON format:
            - full_name: Official full name
            - description: Brief description (2-3 sentences)
            - established_year: Year of establishment
            - board: Education board affiliation  
            - medium: Medium of instruction
            - student_count: Approximate student count
            - faculty_count: Approximate faculty count
            - fee_range: Approximate annual fee range
            - facilities: Array of facilities (e.g., computer lab, library, playground)
            - website: Official website
            - email: Contact email
            - phone: Contact phone
            - principal_name: Principal/Director name
            - address: Full address
            - rating: Rating out of 5 based on reputation
            - social_media: Object with facebook, instagram, twitter URLs
            - pain_points: Array of likely technology/management pain points
            - recommended_approach: Best approach to pitch school management software
            
            Return ONLY valid JSON. No extra text.";

            $response = $this->chat($prompt);
            $parsedData = json_decode($response, true);

            $enrichment->update([
                'raw_response' => ['response' => $response],
                'parsed_data' => $parsedData,
                'status' => 'completed',
            ]);

            // Update lead with enriched data
            if ($parsedData) {
                $lead->update([
                    'ai_data' => $parsedData,
                    'student_count' => $parsedData['student_count'] ?? $lead->student_count,
                    'board' => $parsedData['board'] ?? $lead->board,
                    'website' => $parsedData['website'] ?? $lead->website,
                ]);

                LeadActivity::create([
                    'lead_id' => $lead->id,
                    'type' => 'ai_enriched',
                    'description' => 'Lead enriched with AI data',
                    'metadata' => ['provider' => 'openai'],
                ]);
            }
        } catch (\Exception $e) {
            $enrichment->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            Log::error("AI Enrichment Error for Lead #{$lead->id}: " . $e->getMessage());
        }

        return $enrichment;
    }

    /**
     * Generate personalized email content for a lead
     */
    public function generateEmail(Lead $lead, string $purpose = 'introduction'): array
    {
        $context = "School: {$lead->school_name}, City: {$lead->city}, Type: {$lead->type}";
        if ($lead->student_count) $context .= ", Students: {$lead->student_count}";
        if ($lead->board) $context .= ", Board: {$lead->board}";
        if ($lead->contact_person) $context .= ", Contact: {$lead->contact_person}";

        $aiData = '';
        if ($lead->ai_data) {
            $painPoints = $lead->ai_data['pain_points'] ?? [];
            if (!empty($painPoints)) {
                $aiData = "\nKnown pain points: " . implode(', ', $painPoints);
            }
        }

        $purposeInstructions = match($purpose) {
            'introduction' => 'Write a warm introduction email explaining our school management ERP software and how it can help their institution.',
            'follow_up' => 'Write a follow-up email. Be friendly and reference that we previously reached out.',
            'demo_invite' => 'Write an email inviting them to schedule a free demo of our software.',
            'proposal' => 'Write an email with a proposal overview, highlighting key features and pricing benefits.',
            'case_study' => 'Write an email sharing a success story of how similar institutions benefited from our software.',
            default => 'Write a professional outreach email about our school management software.',
        };

        $prompt = "You are writing an email on behalf of a school management ERP software company.
        
        Target institution details: {$context}{$aiData}
        
        Purpose: {$purposeInstructions}
        
        Requirements:
        - Keep it concise (under 200 words)
        - Personalize using the school name and details
        - Highlight 2-3 key benefits relevant to their institution type
        - Include a clear call to action
        - Professional but friendly tone
        - Don't use generic language
        
        Return JSON with:
        - subject: Email subject line
        - body: HTML email body (use basic HTML for formatting)
        - preview_text: Short preview text (under 100 chars)";

        try {
            $response = $this->chat($prompt);
            $result = json_decode($response, true);
            return $result ?? ['subject' => '', 'body' => '', 'preview_text' => ''];
        } catch (\Exception $e) {
            Log::error('AI Email Generation Error: ' . $e->getMessage());
            return ['subject' => '', 'body' => '', 'preview_text' => '', 'error' => $e->getMessage()];
        }
    }

    /**
     * Score a lead using AI analysis
     */
    public function scoreLead(Lead $lead): int
    {
        $prompt = "Score this educational institution lead from 0-100 based on how likely they are to purchase school management software.

        Institution: {$lead->school_name}
        Type: {$lead->type}
        City: {$lead->city}, State: {$lead->state}
        Students: " . ($lead->student_count ?: 'Unknown') . "
        Board: " . ($lead->board ?: 'Unknown') . "
        Has website: " . ($lead->website ? 'Yes' : 'No') . "
        Has email: " . ($lead->email ? 'Yes' : 'No') . "
        Current status: {$lead->status}
        
        Scoring criteria:
        - Higher student count = higher score (schools with 500+ students need software more)
        - Urban locations score higher
        - Having a website suggests tech readiness
        - CBSE/ICSE schools tend to adopt tech faster
        - Colleges/universities score higher than small coaching centers
        
        Return ONLY a JSON object: {\"score\": number, \"reasoning\": \"brief explanation\"}";

        try {
            $response = $this->chat($prompt);
            $result = json_decode($response, true);
            $score = min(100, max(0, (int) ($result['score'] ?? 50)));

            $lead->update(['lead_score' => $score]);

            LeadActivity::create([
                'lead_id' => $lead->id,
                'type' => 'score_update',
                'description' => "Lead score updated to {$score}/100 by AI: " . ($result['reasoning'] ?? ''),
                'metadata' => $result,
            ]);

            return $score;
        } catch (\Exception $e) {
            Log::error("AI Lead Scoring Error for Lead #{$lead->id}: " . $e->getMessage());
            return $lead->lead_score;
        }
    }

    /**
     * Core chat completion call
     */
    protected function chat(string $prompt): string
    {
        if (empty($this->apiKey)) {
            throw new \RuntimeException('OpenAI API key is not configured. Set OPENAI_API_KEY in .env');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post("{$this->baseUrl}/chat/completions", [
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that returns data in valid JSON format only. No markdown, no extra text.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        if ($response->failed()) {
            throw new \RuntimeException('OpenAI API error: ' . $response->body());
        }

        return $response->json('choices.0.message.content', '{}');
    }
}
