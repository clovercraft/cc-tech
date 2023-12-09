<?php

namespace App\View\Components\CMS;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Contenet extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.c-m-s.contenet');
    }
}
