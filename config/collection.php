<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Collection Forecast Probabilities
    |--------------------------------------------------------------------------
    |
    | Define the probabilities used for forecasting expected collections
    | based on the ageing buckets.
    |
    */
    'probabilities' => [
        '0-30' => 0.90,
        '31-60' => 0.70,
        '61-90' => 0.50,
        '91-120' => 0.30,
        '120+' => 0.20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Reminder Thresholds
    |--------------------------------------------------------------------------
    |
    | Define the overdue days at which specific reminder levels are triggered.
    |
    */
    'reminders' => [
        '1st Reminder' => 5,
        '2nd Reminder' => 13,
        '3rd Reminder' => 30,
        'Final Notice' => 90,
        'Escalated' => 120,
        'Critical Recovery' => 180,
    ],

    /*
    |--------------------------------------------------------------------------
    | Risk Classifications
    |--------------------------------------------------------------------------
    |
    | Risk levels based on ageing buckets.
    |
    */
    'risk_levels' => [
        '0-30' => 'Low',
        '31-60' => 'Medium',
        '61-90' => 'High',
        '91-120' => 'Critical',
        '120+' => 'Severe',
    ],
];
