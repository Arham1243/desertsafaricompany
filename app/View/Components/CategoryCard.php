<?php

namespace App\View\Components;

use Illuminate\View\Component;

class CategoryCard extends Component
{
    public $category;

    public $detailUrl;

    public $style;

    public function __construct($category, $style = 'style1')
    {
        $this->category = $category;
        $this->style = $style;
        $this->detailUrl = buildCategoryDetailUrl($category);
    }

    public function render()
    {
        return view('components.category-card');
    }
}
