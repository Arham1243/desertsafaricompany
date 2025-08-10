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
        $this->detailUrl = buildTourDetailUrl($tour);
    }

    public function render()
    {
        return view('components.tour-card');
    }
}
