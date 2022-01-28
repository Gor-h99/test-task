<?php

use Carbon\CarbonInterface;

return [
    'working_days' => [
        CarbonInterface::MONDAY,
        CarbonInterface::WEDNESDAY,
        CarbonInterface::FRIDAY,
    ],
    'working_between' => ['08:00', '16:00'],
    'lunch_between' => ['12:00', '12:45'],
    'other_week_working_days' => [
        CarbonInterface::SATURDAY,
    ],
    'other_week_calculate_from' => '2022-01-24 00:00:00',
];
