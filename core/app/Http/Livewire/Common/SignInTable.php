<?php

namespace App\Http\Livewire\Common;

use App\Http\Livewire\Traits\Sorter;
use Livewire\Component;
use App\Models\SignIn;

class SignInTable extends Component
{
    use Sorter;

    /**
     *
     * id Автора
     * @var int $whoId
     */
    public $whoId;

    /**
     *
     * Тип Автора
     * @var string $whoType
     */
    public $whoType;

    /**
     *
     * Запускается при монтировании компонента
     * @param int $id
     */
    public function mount(int $whoId, string $whoType): void
    {
        $this->whoId = $whoId;
        $this->whoType = $whoType;
    }

    public function render()
    {
        $logs = SignIn::where('who_id', $this->whoId)
            ->where('who', $this->whoType)
            ->orderBy($this->sortField, $this->sortType)
            ->paginate(10);
        return view('livewire.common.signins-table', compact('logs'));
    }
}
