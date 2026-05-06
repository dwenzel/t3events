<?php

declare(strict_types=1);

use DWenzel\T3events\Domain\Model\Content;
use DWenzel\T3events\Domain\Model\Person;
use DWenzel\T3events\Domain\Model\Category;
use DWenzel\T3events\Domain\Model\Event;

return [
    Content::class => [
        'tableName' => 'tt_content',
        'properties' => [
            'altText' => [
                'fieldName' => 'altText'
            ],
            'titleText' => [
                'fieldName' => 'titleText'
            ],
            'colPos' => [
                'fieldName' => 'colPos'
            ],
            'CType' => [
                'fieldName' => 'CType'
            ],
        ]
    ],

    Person::class => [
        'properties' => [
            'type' => [
                'fieldName' => 'tx_extbase_type'
            ],
        ],
    ],

    Category::class => [
        'tableName' => 'sys_category'
    ],
    Event::class => [
        'tableName' => 'tx_t3events_domain_model_event',
        'properties' => [
            'crdate' => [
                'fieldName' => 'crdate',
            ],
            'tstamp' => [
                'fieldName' => 'tstamp',
            ],
        ]

    ]
];
