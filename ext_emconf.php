<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Events',
    'description' => 'Manage events, show teasers, list and single views.',
    'category' => 'plugin',
    'version' => '2.0.0',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearcacheonload' => 0,
    'author' => 'Dirk Wenzel, Michael Kasten',
    'author_email' => 't3events@gmx.de, kasten@webfox01.de',
    'author_company' => 'coding. powerful. systems. CPS GmbH',
    'constraints' =>
        [
            'depends' =>
                [
                    'typo3' => '11.5.0-11.5.99',
                    't3extension_tools' => '2.0.0-2.99.99'
                ],
            'conflicts' =>
                [],
            'suggests' =>
                [],
        ]
];

