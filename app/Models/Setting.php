<?php

namespace App\Models;

use App\Traits\UploadImageTrait;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use UploadImageTrait;

    protected $fillable = ['group', 'key', 'value'];

    public static function get($key, $default = null)
    {
        return self::where('key', $key)->value('value') ?? $default;
    }

    public static function set($key, $value, $group = 'general')
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }

    public static function setFile($key, $file, $group = 'general')
    {
        if ($file) {
            $previousImage = self::where('key', $key)->value('value');

            $path = (new self)->simpleUploadImg($file, "Settings/$group", $previousImage);

            return self::set($key, $path, $group);
        }

        return null;
    }

    public static function getFileUrl($key, $default = null)
    {
        $path = self::get($key);

        return $path ? asset("$path") : $default;
    }
}
