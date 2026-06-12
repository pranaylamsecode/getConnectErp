<?php

namespace App\Livewire\Teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Team Management')]
class TeamManager extends Component
{
    public string $newMemberEmail = '';
    public string $newMemberRole = 'sales';

    public function addMember(): void
    {
        $this->validate([
            'newMemberEmail' => 'required|email',
            'newMemberRole' => 'required|in:sales,manager',
        ]);

        $user = User::where('email', $this->newMemberEmail)->first();

        if ($user) {
            $user->update([
                'team_id' => Auth::user()->team_id,
                'role' => $this->newMemberRole,
            ]);
            session()->flash('message', "{$user->name} added to team.");
        } else {
            User::create([
                'name' => explode('@', $this->newMemberEmail)[0],
                'email' => $this->newMemberEmail,
                'password' => Hash::make('password123'),
                'role' => $this->newMemberRole,
                'team_id' => Auth::user()->team_id,
                'is_active' => true,
            ]);
            session()->flash('message', "User created and added to team. Default password: password123");
        }

        $this->reset(['newMemberEmail', 'newMemberRole']);
    }

    public function render()
    {
        $user = Auth::user();
        $team = Team::with(['owner', 'members'])->find($user->team_id);
        $members = $team ? $team->members : collect();

        return view('livewire.teams.team-manager', compact('team', 'members'));
    }
}
