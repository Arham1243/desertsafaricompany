<?php

namespace App\Traits;

use App\Models\Schema;

trait HasSchema
{
    /**
     * Get the schema for this model instance
     */
    public function getSchema($schemaType = null)
    {
        $entityTypeMap = [
            'App\\Models\\Tour' => 'tours',
            'App\\Models\\Page' => 'pages',
            'App\\Models\\Blog' => 'blogs',
            'App\\Models\\News' => 'news',
            'App\\Models\\City' => 'cities',
            'App\\Models\\Country' => 'countries',
            'App\\Models\\TourCategory' => 'tour-categories',
        ];

        $entityType = $entityTypeMap[get_class($this)] ?? null;

        if (!$entityType) {
            return [];
        }

        // For tours, use schema_type if not provided
        if ($entityType === 'tours' && !$schemaType && isset($this->schema_type)) {
            $schemaType = $this->schema_type;
        }

        return Schema::getSchema($entityType, $this->id, $schemaType);
    }

    /**
     * Save schema for this model instance
     */
    public function saveSchema($schemaJson, $schemaType = null)
    {
        $entityTypeMap = [
            'App\\Models\\Tour' => 'tours',
            'App\\Models\\Page' => 'pages',
            'App\\Models\\Blog' => 'blogs',
            'App\\Models\\News' => 'news',
            'App\\Models\\City' => 'cities',
            'App\\Models\\Country' => 'countries',
            'App\\Models\\TourCategory' => 'tour-categories',
        ];

        $entityType = $entityTypeMap[get_class($this)] ?? null;

        if (!$entityType) {
            return null;
        }

        // For tours, use schema_type if not provided
        if ($entityType === 'tours' && !$schemaType && isset($this->schema_type)) {
            $schemaType = $this->schema_type;
        }

        return Schema::saveSchema($entityType, $this->id, $schemaJson, $schemaType);
    }

    /**
     * Delete schema for this model instance
     */
    public function deleteSchema($schemaType = null)
    {
        $entityTypeMap = [
            'App\\Models\\Tour' => 'tours',
            'App\\Models\\Page' => 'pages',
            'App\\Models\\Blog' => 'blogs',
            'App\\Models\\News' => 'news',
            'App\\Models\\City' => 'cities',
            'App\\Models\\Country' => 'countries',
            'App\\Models\\TourCategory' => 'tour-categories',
        ];

        $entityType = $entityTypeMap[get_class($this)] ?? null;

        if (!$entityType) {
            return false;
        }

        return Schema::deleteSchema($entityType, $this->id, $schemaType);
    }
}
