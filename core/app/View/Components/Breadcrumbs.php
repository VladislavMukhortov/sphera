<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    public $title = false;
    public $parents = [];

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(string $title, ? array $parents = [])
    {
        $this->title = $title;
        $this->parents = $parents;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.breadcrumbs');
    }
}
