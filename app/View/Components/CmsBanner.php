<?php

namespace App\View\Components;

use App\Models\CMS\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CmsBanner extends Component
{

    public $banner_path = 'img/default-banner.png';

    /**
     * Create a new component instance.
     */
    public function __construct(Page $page = null)
    {
        if (!empty($page) && !empty($page->cover_img)) {
            $this->banner_path = $page->cover_img;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cms-banner');
    }
}
