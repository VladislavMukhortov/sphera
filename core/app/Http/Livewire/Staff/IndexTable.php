<?php

namespace App\Http\Livewire\Staff;

use App\Http\Livewire\Traits\Sorter;
use Livewire\Component;
use App\Models\Staff;

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
        $staff = Staff::when(
            $this->search,
            fn($q) => $q->where('name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
        )
            ->orderBy($this->sortField, $this->sortType)->paginate(10);
        return view('livewire.staff.index-table', compact('staff'));
    }
}
