<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TourCard extends Component
{
    public $tour;

    public $style;

    public $detailUrl;

    public function __construct($tour, $style = 'style1')
    {
        $this->tour = $tour;
        $this->style = $style;

        $country = strtolower($tour->category->country->iso_alpha2 ?? 'xx');
        $city = $tour->category->city->slug ?? 'no-city';
        $category = $tour->category->slug ?? 'no-category';
        $slug = $tour->slug ?? 'no-slug';

        $this->detailUrl = url("$country/$city/$category/$slug");
    }

    public function render()
    {
        return view('components.tour-card');
    }
}
