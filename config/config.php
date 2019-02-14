<?php

return [
    'elastic' => [
        'host' => getenv('ELASTICSEARCH_HOST'),
        'port' => getenv('ELASTICSEARCH_PORT'),
    ],
    'search.priceranges' => [
        [
            "from" => 0,
            "to"   => 1.99
        ],
        [
            "from" => 2,
            "to"   => 3.99
        ],
        [
            "from" => 4,
            "to"   => 5.99
        ],
        [
            "from" => 6,
            "to"   => 9.99
        ],
        [
            "from" => 10
        ]
    ],
    'search.ratingranges' => [
        [
            "from" => 0,
            "to"   => 5
        ],
        [
            "from" => 1,
            "to"   => 5
        ],
        [
            "from" => 2,
            "to"   => 5
        ],
        [
            "from" => 3,
            "to"   => 5
        ],
        [
            "from" => 4,
            "to" => 5
        ],
        [
            "from" => 5,
            "to" => 5
        ]
    ]
];
