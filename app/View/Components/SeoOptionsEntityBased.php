<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SeoOptionsEntityBased extends Component
{
    public $seo;

    public $resource;

    public $entity;

    public $slug;

    public $id;
    public $schemaEntity;

    public function __construct($seo = null, $resource = null, $entity = null, $slug = null, $id = null, $schemaEntity = null)
    {
        $this->seo = $seo;
        $this->resource = $resource;
        $this->entity = $entity;
        $this->slug = $slug;
        $this->id = $id;
        $this->schemaEntity = $schemaEntity;
    }

    public function render()
    {
        return view('components.seo-options-entity-based');
    }
}
