<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextInput extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $disabled = false,
        public $type = 'text',
        public $name = null,
        public $id = null,
        public $value = null,
        public $required = false,
        public $autofocus = false,
        public $autocomplete = null,
        public $placeholder = null
    ) {
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