<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $disabled = false)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.text-input');
    }
} 