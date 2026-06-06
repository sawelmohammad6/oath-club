<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteSetting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $table = 'website_settings';

    public static function getValue($key, $default = '')
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function setValue($key, $value)
    {
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function getAllAsArray()
    {
        $settings = self::all();
        $data = [];
        foreach ($settings as $s) {
            $data[$s->key] = $s->value;
        }
        return $data;
    }
}
