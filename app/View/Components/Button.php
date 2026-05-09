<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Button extends Component
{
    public string $type;
    public ?string $href;
    public string $buttonType;

    public function __construct(string $type = 'primary', ?string $href = null, string $buttonType = 'submit')
    {
        $this->type = $type;
        $this->href = $href;
        $this->buttonType = $buttonType;
    }

    public function render(): View|Closure|string
    {
        return view('components.button', [
            'variantClasses' => $this->variantClasses(),
        ]);
    }

    private function variantClasses(): string
    {
        return match ($this->type) {
            'secondary' => 'btn btn-outline-secondary btn-sm',
            'danger' => 'btn btn-danger btn-sm',
            default => 'btn btn-primary btn-sm',
        };
    }
}
