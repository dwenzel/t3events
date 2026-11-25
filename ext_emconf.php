<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Events',
    'description' => 'Manage events, show teasers, list and single views.',
    'category' => 'plugin',
    'version' => '4.0.2',
    'state' => 'stable',
    'author' => 'Dirk Wenzel, Michael Kasten',
    'author_email' => 't3events@gmx.de, kasten@webfox01.de',
    'author_company' => 'Agentur Webfox GmbH, Consulting Piezunka Schamoni - Information Technologies GmbH',
    'constraints' => [
        'depends' => [
            'php' => '8.3.0-8.5.99',
            'typo3' => '13.4.0-14.3.99',
            't3extension_tools' => '5.0.0-6.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
