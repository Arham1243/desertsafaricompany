<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoOptionsEntityBased extends Component
{
    public $seo;

    public $resource;

    public $entity;

    public $slug;

    public function __construct($seo = null, $resource = null, $entity = null, $slug = null)
    {
        $this->seo = $seo;
        $this->resource = $resource;
        $this->entity = $entity;
        $this->slug = $slug;
    }

    public function render()
    {
        return view('components.seo-options-entity-based');
    }
}
