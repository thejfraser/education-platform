<?php

namespace App\View\Components;

use Illuminate\View\Component;

class PaginationComponent extends Component
{
    public $currentPage;
    public $maxPage;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(int $currentPage, int $maxPage)
    {
        $this->currentPage = $currentPage;
        $this->maxPage = $maxPage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.pagination');
    }
}
