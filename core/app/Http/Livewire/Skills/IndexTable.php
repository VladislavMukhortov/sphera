<?php

namespace App\Http\Livewire\Skills;

use App\Http\Livewire\Traits\Sorter;
use App\Models\Skill;
use Livewire\Component;

class IndexTable extends Component
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
            ->when($this->search,
                fn($q) => $q->whereHas('locale', fn($q) => $q->where('title', 'like', "%{$this->search}%"))
            )
            ->when($this->sortField == 'title',
                fn($q) => $q->join('skill_locales', 'skills.id', '=', 'skill_locales.skill_id')
                    ->whereLang(app()->getLocale())
                    ->select('skills.*', 'skill_locales.title')
                    ->orderBy($this->sortField, $this->sortType)
                    ->paginate(10),
                fn($q) => $q->orderBy($this->sortField, $this->sortType)->paginate(10)
            );

        return view('livewire.skills.index-table', compact('skill'));
    }
}
