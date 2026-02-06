<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Button extends Component
{
    public $variant;
    public $size;
    public $class;
    public $icon;
    public $type;

    public function __construct(
        $variant = 'secondary',
        $size = 'md',
        $class = '',
        $icon = null,
        $type = 'button'
    ) {
        $this->variant = $variant;
        $this->size = $size;
        $this->class = $class;
        $this->icon = $icon;
        $this->type = $type;
    }

    public function render()
    {
        return view('components.button');
    }
}