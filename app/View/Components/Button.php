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
            'secondary' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-400 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-offset-2',
            'danger' => 'inline-flex items-center justify-center gap-2 rounded-lg border border-rose-600 bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:border-rose-700 hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2',
            default => 'inline-flex items-center justify-center gap-2 rounded-lg border border-sky-600 bg-sky-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:border-sky-700 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2',
        };
    }
}
