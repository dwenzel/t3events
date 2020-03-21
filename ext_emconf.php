<?php
$EM_CONF['t3events'] = array(
    'title' => 'Events',
    'description' => 'Manage events, show teasers, list and single views.',
    'category' => 'plugin',
    'version' => '1.1.0',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearcacheonload' => 0,
    'author' => 'Dirk Wenzel, Michael Kasten',
    'author_email' => 't3events@gmx.de, kasten@webfox01.de',
    'author_company' => 'Agentur Webfox GmbH, Consulting Piezunka Schamoni - Information Technologies GmbH',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-9.5.99',
            't3calendar' => '0.4.0-0.0.0'
        ],
        'conflicts' => [],
        'suggests' => [],
    ]
);

