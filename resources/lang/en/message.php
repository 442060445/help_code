<?php

return [
    'add-success' => 'Add Success',
    'unknown-type' => 'Unknown Your Activity In Our System,We Will Upgrade Soon',
    'exist' => "Code Is Exists, Please Don't add it again",
    'read-success' => 'Read Code Success',
    'count' => 'Number of people currently boarding:',
    'clean-day' => 'Database Will Clear In Per Month ',
    'clean-day-end' => '.',
    'type_frequently' => 'You Are Too Frequently Request For A Type,Please Request Again After '
        .env('TYPE_MAX_IP_LIMIT_TIME',600).' Second .',
    'frequently' => 'You Are Too Frequently Request For This Project ,Please Request Again After '
        .env('MAX_IP_LIMIT_TIME',600).' Second .',
    'not-allow-keyword' => 'Check That There Are Keywords That Are Not Allowed To Be Added'
];
