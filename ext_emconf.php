<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Events',
    'description' => 'Manage events, show teasers, list and single views.',
    'category' => 'plugin',
    'version' => '3.0.1',
    'state' => 'stable',
    'clearcacheonload' => 0,
    'author' => 'Dirk Wenzel, Michael Kasten',
    'author_email' => 't3events@gmx.de, kasten@webfox01.de',
    'author_company' => 'Agentur Webfox GmbH, Consulting Piezunka Schamoni - Information Technologies GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'php' => '8.1.0-8.4.99',
            't3extension_tools' => '4.0.0-4.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
