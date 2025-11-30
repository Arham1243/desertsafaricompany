<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schema extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_type',
        'entity_id',
        'schema_type',
        'schema_json',
    ];

    protected $casts = [
        'schema_json' => 'array',
    ];

    /**
     * Get schema for a specific entity
     */
    public static function getSchema($entityType, $entityId, $schemaType = null)
    {
        $query = self::where('entity_type', $entityType)
            ->where('entity_id', (string) $entityId);

        if ($schemaType) {
            $query->where('schema_type', $schemaType);
        }

        $schemaRecord = $query->first();

        if (!$schemaRecord) {
            return [];
        }

        // The schema_json is cast to array, so it should already be decoded
        $schemaData = $schemaRecord->schema_json;

        // If it's still a string (shouldn't happen with cast, but just in case), decode it
        if (is_string($schemaData)) {
            $decoded = json_decode($schemaData, true);
            return $decoded ?? [];
        }

        return is_array($schemaData) ? $schemaData : [];
    }

    /**
     * Save or update schema for an entity
     */
    public static function saveSchema($entityType, $entityId, $schemaJson, $schemaType = null)
    {
        return self::updateOrCreate(
            [
                'entity_type' => $entityType,
                'entity_id' => (string) $entityId,
                'schema_type' => $schemaType,
            ],
            [
                'schema_json' => is_string($schemaJson) ? $schemaJson : json_encode($schemaJson, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            ]
        );
    }

    /**
     * Delete schema for an entity
     */
    public static function deleteSchema($entityType, $entityId, $schemaType = null)
    {
        $query = self::where('entity_type', $entityType)
            ->where('entity_id', $entityId);

        if ($schemaType) {
            $query->where('schema_type', $schemaType);
        }

        return $query->delete();
    }
}
