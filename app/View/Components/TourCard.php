<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TourCard extends Component
{
    public $tour;

    public $style;

    /**
     * Create a new component instance.
     *
     * @param  mixed  $tour
     * @param  string  $style
     * @return void
     */
    public function __construct($tour, $style = 'style1')
    {
        $this->tour = $tour;
        $this->style = $style;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.tour-card');
    }
}
