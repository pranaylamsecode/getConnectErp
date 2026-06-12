<?php

namespace App\Livewire\Templates;

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Email Templates')]
class TemplateIndex extends Component
{
    public string $name = '';
    public string $subject = '';
    public string $html_content = '';
    public string $category = 'general';
    public ?int $editingId = null;

    protected $rules = [
        'name' => 'required|string|max:255',
        'subject' => 'required|string|max:255',
        'html_content' => 'required|string',
        'category' => 'required|string',
    ];

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            $template = EmailTemplate::findOrFail($this->editingId);
            $template->update([
                'name' => $this->name,
                'subject' => $this->subject,
                'html_content' => $this->html_content,
                'category' => $this->category,
            ]);
            session()->flash('message', 'Template updated.');
        } else {
            EmailTemplate::create([
                'name' => $this->name,
                'subject' => $this->subject,
                'html_content' => $this->html_content,
                'category' => $this->category,
                'created_by' => Auth::id(),
            ]);
            session()->flash('message', 'Template created.');
        }

        $this->reset(['name', 'subject', 'html_content', 'category', 'editingId']);
    }

    public function editTemplate(int $id): void
    {
        $template = EmailTemplate::findOrFail($id);
        $this->editingId = $id;
        $this->name = $template->name;
        $this->subject = $template->subject;
        $this->html_content = $template->html_content;
        $this->category = $template->category;
    }

    public function deleteTemplate(int $id): void
    {
        EmailTemplate::findOrFail($id)->delete();
        session()->flash('message', 'Template deleted.');
    }

    public function render()
    {
        $templates = EmailTemplate::orderByDesc('created_at')->get();
        return view('livewire.templates.template-index', compact('templates'));
    }
}
