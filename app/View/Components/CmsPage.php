<?php

namespace App\View\Components;

use App\Models\CMS\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CmsPage extends Component
{
    public $title = '';
    public $hasSubtitle = false;
    public $subtitle = '';
    public $content = '';

    /**
     * Create a new component instance.
     */
    public function __construct(Page $page)
    {
        $this->title = $page->title;
        $this->hasSubtitle = !empty($page->subtitle);
        if ($this->hasSubtitle) {
            $this->subtitle = $page->subtitle;
        }
        $this->content = $page->content;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cms-page');
    }
}
