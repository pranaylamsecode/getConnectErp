<?php

namespace Database\Seeders;

use App\Models\LeadSource;
use App\Models\LeadStage;
use App\Models\Tag;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@getconnect.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Create default team
        $team = Team::create([
            'name' => 'Sales Team',
            'slug' => 'sales-team',
            'description' => 'Main sales team',
            'owner_id' => $admin->id,
        ]);

        $admin->update(['team_id' => $team->id]);

        // Create demo team members
        User::create([
            'name' => 'Rahul Sharma',
            'email' => 'rahul@getconnect.com',
            'password' => bcrypt('password'),
            'role' => 'manager',
            'team_id' => $team->id,
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Priya Singh',
            'email' => 'priya@getconnect.com',
            'password' => bcrypt('password'),
            'role' => 'sales',
            'team_id' => $team->id,
            'is_active' => true,
        ]);

        // Lead Sources
        $sources = [
            ['name' => 'Website', 'icon' => '🌐', 'color' => '#3b82f6'],
            ['name' => 'Referral', 'icon' => '🤝', 'color' => '#10b981'],
            ['name' => 'Cold Email', 'icon' => '✉️', 'color' => '#f59e0b'],
            ['name' => 'AI Discovered', 'icon' => '🤖', 'color' => '#8b5cf6'],
            ['name' => 'Social Media', 'icon' => '📱', 'color' => '#ec4899'],
            ['name' => 'Phone Call', 'icon' => '📞', 'color' => '#06b6d4'],
            ['name' => 'Event/Exhibition', 'icon' => '🎪', 'color' => '#f97316'],
            ['name' => 'Manual Entry', 'icon' => '📝', 'color' => '#6b7280'],
        ];

        foreach ($sources as $source) {
            LeadSource::create($source);
        }

        // Lead Stages (Pipeline)
        $stages = [
            ['name' => 'New Lead', 'slug' => 'new-lead', 'order' => 1, 'color' => '#6366f1', 'is_default' => true],
            ['name' => 'Contacted', 'slug' => 'contacted', 'order' => 2, 'color' => '#3b82f6'],
            ['name' => 'Qualified', 'slug' => 'qualified', 'order' => 3, 'color' => '#06b6d4'],
            ['name' => 'Demo Scheduled', 'slug' => 'demo-scheduled', 'order' => 4, 'color' => '#8b5cf6'],
            ['name' => 'Proposal Sent', 'slug' => 'proposal-sent', 'order' => 5, 'color' => '#f59e0b'],
            ['name' => 'Negotiation', 'slug' => 'negotiation', 'order' => 6, 'color' => '#f97316'],
            ['name' => 'Won', 'slug' => 'won', 'order' => 7, 'color' => '#10b981', 'is_won' => true],
            ['name' => 'Lost', 'slug' => 'lost', 'order' => 8, 'color' => '#ef4444', 'is_lost' => true],
        ];

        foreach ($stages as $stage) {
            LeadStage::create($stage);
        }

        // Tags
        $tags = [
            ['name' => 'CBSE', 'color' => '#3b82f6'],
            ['name' => 'ICSE', 'color' => '#8b5cf6'],
            ['name' => 'State Board', 'color' => '#10b981'],
            ['name' => 'High Priority', 'color' => '#ef4444'],
            ['name' => 'Enterprise', 'color' => '#f59e0b'],
            ['name' => 'Small School', 'color' => '#06b6d4'],
            ['name' => 'Large Institution', 'color' => '#ec4899'],
            ['name' => 'Tech Ready', 'color' => '#84cc16'],
            ['name' => 'Follow Up', 'color' => '#f97316'],
            ['name' => 'Hot Lead', 'color' => '#ef4444'],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
