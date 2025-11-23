<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoOptions extends Component
{
    public $seo;

    public $resource;

    public $slug;
    public $id;
    public $entity;

    public function __construct($seo = null, $resource = null, $slug = null, $id = null, $entity = null)
    {
        $this->seo = $seo;
        $this->resource = $resource;
        $this->slug = $slug;
        $this->id = $id;
        $this->entity = $entity;
    }

    public function render()
    {
        return view('components.seo-options');
    }
}
