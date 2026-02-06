<?php
namespace App\View\Components;

use Illuminate\View\Component;

class ButtonLink extends Component
{
    public $href;
    public $variant;
    public $class;
    public $icon;

    public function __construct($href = '#', $variant = 'secondary', $class = '', $icon = null)
    {
        $this->href = $href;
        $this->variant = $variant;
        $this->class = $class;
        $this->icon = $icon;
    }

    public function render()
    {
        return view('components.button-link');
    }
}
