<?php

namespace App\View\Components;

use App\Models\CMS\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CmsMenu extends Component
{

    public $items;
    public $current = '';

    /**
     * Create a new component instance.
     */
    public function __construct(string $current = '')
    {
        $this->current = $current;
        $this->items = collect([]);
        $this->setDefaultMenu();
        $this->preparePagesMenu();
    }

    private function setDefaultMenu(): void
    {
        $this->items->push(
            new MenuItem(route('front.home'), 'Home'),
            new MenuItem('https://wiki.clovercraft.gg', 'Wiki'),
            new MenuItem('https://discord.gg/clovercraft', 'Discord'),
            new MenuItem('https://www.patreon.com/clovercraft', 'Patreon')
        );
    }

    private function preparePagesMenu(): void
    {
        $pages = Page::all();
        $pageItems = [];
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.cms-menu', ['items' => $this->items]);
    }
}

class MenuItem
{
    public function __construct(
        public string $route,
        public string $label,
        public bool $isParent = false,
        public array $children = []
    ) {
    }
}
