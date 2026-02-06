<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Info extends Component
{
    public ?string $label;
    public string $value;
    public ?string $link;
    public string $variant;

    public function __construct(
        ?string $label = null,
        string $value = '',
        ?string $link = null,
        string $variant = 'default'
    ) {
        $this->label = $label;
        $this->value = $value;
        $this->link = $link;
        $this->variant = $variant;
    }

    public function render()
    {
        return view('components.info');
    }
}
