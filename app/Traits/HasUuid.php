<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    /**
     * Boot the UUID trait to automatically assign a UUID key during creation.
     */
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            $keyName = $model->getKeyName();
            if (empty($model->{$keyName})) {
                $model->{$keyName} = (string) Str::uuid();
            }
        });
    }

    /**
     * Indicate that the primary key is not auto-incrementing.
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Set the primary key type to string.
     */
    public function getKeyType()
    {
        return 'string';
    }
}
