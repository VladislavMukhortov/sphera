<?php

namespace App\Http\Livewire\Users;

use App\Http\Livewire\Traits\Sorter;
use Livewire\Component;
use App\Models\User;

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
        $users = User::when(
            $this->search,
            fn($q) => $q->where('first_name', 'like', "%{$this->search}%")
                ->orWhere('email', 'like', "%{$this->search}%")
                ->orWhere('last_name', 'like', "%{$this->search}%")
        )
            ->orderBy($this->sortField, $this->sortType)->paginate(10);
        return view('livewire.users.index-table', compact('users'));
    }
}
