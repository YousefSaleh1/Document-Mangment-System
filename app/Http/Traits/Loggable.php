<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Log;

trait Loggable
{

    /**
     * Boot the loggable functionality for the model.
     *
     * This method registers event listeners for the created, updated, and deleted events
     * on the model to log the corresponding actions.
     *
     * @return void
     */
    public static function bootLoggable()
    {
        static::created(function ($model) {
            Log::info('Created: ' . get_class($model) . ' ID: ' . $model->id);
        });

        static::updated(function ($model) {
            Log::info('Updated: ' . get_class($model) . ' ID: ' . $model->id);
        });

        static::deleted(function ($model) {
            Log::info('Deleted: ' . get_class($model) . ' ID: ' . $model->id);
        });
    }
}
