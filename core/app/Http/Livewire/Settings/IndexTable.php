<?php

namespace App\Http\Livewire\Settings;

use App\Http\Livewire\Traits\Sorter;
use App\Models\Setting;
use Illuminate\View\View;
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
    public string $search;

    /**
     * @return View
     */
    public function render(): View
    {
        $settings = Setting::orderBy($this->sortField, $this->sortType)->paginate(10);

        return view('livewire.settings.index-table', compact('settings'));
    }
}
