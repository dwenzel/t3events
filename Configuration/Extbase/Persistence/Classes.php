<?php

return [
    DWenzel\T3events\Domain\Model\Content::class => [
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

    DWenzel\T3events\Domain\Model\Person::class => [
        'properties' => [
            'type' => [
                'fieldName' => 'tx_extbase_type'
            ],
        ],
    ],

    DWenzel\T3events\Domain\Model\Category::class => [
        'tableName' => 'sys_category'
    ],
    \DWenzel\T3events\Domain\Model\Event::class => [
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
