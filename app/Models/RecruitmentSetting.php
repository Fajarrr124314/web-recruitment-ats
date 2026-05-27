<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruitmentSetting extends Model
{
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Get a setting value by key, with dynamic default value.
     */
    public static function getValue(string $key, $default = null)
    {
        $setting = self::find($key);
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value by key.
     */
    public static function setValue(string $key, $value): self
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Automatically activate global recruitment if scheduled time has passed.
     */
    public static function checkAndAutoActivate()
    {
        $scheduledOpenAtVal = self::getValue('scheduled_open_at', '');
        if ($scheduledOpenAtVal) {
            $scheduledTime = \Illuminate\Support\Carbon::parse($scheduledOpenAtVal, 'Asia/Jakarta');
            if (now('Asia/Jakarta')->greaterThanOrEqualTo($scheduledTime)) {
                self::setValue('is_active', '1');
                self::setValue('scheduled_open_at', null);
            }
        }
    }
}
