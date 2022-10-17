<?php

namespace App\Http\Livewire\Moderation;

use App\Http\Livewire\Traits\Sorter;
use App\Models\Skill;
use Livewire\Component;

class SkillsTable extends Component
{
    use Sorter;

    /**
     *
     * Искомая фраза
     * @var string
     *
     */
    public $search;

    public function render()
    {
        $skill = Skill::with(['parent', 'locale'])
            ->where('is_allowed', 0)
            ->when(
                $this->search,
                fn($q) => $q->where('title', 'like', "%{$this->search}%")
            )
            ->orderBy($this->sortField, $this->sortType)->paginate(10);

        return view('livewire.moderation.skills-table', compact('skill'));
    }
}
