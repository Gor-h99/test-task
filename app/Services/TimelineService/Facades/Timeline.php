<?php

namespace App\Services\TimelineService\Facades;

use App\Services\TimelineService\TimelineService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static checkStatus(mixed $data)
 * @method static getOpenInfo($dateTime)
 * @method static getNextOpeningTime($dateTime)
 *
 * @see TimelineService
 */
class Timeline extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return TimelineService::class;
    }
}
