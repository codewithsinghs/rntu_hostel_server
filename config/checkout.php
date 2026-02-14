<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Checkout Clearance Workflow
    |--------------------------------------------------------------------------
    | Order matters. Resident initiates, others approve.
    */

    'workflow' => [
        [
            'role'  => 'warden',
            'order' => 1,
        ],
        [
            'role'  => 'admin',
            'order' => 2,
        ],
        [
            'role'  => 'accountant',
            'order' => 3,
        ],
    ],

];
