<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    public $align;
    public $width;

    /**
     * Create a new component instance.
     *
     * @param string $align
     * @param string $width
     * @return void
     */
    public function __construct($align = 'right', $width = '48')
    {
        $this->align = $align;
        $this->width = $width;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.dropdown');
    }
} 