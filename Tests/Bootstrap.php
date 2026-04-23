<?php

// Define TYPO3 global constants needed for unit tests
defined('LF') ?: define('LF', chr(10));
defined('CR') ?: define('CR', chr(13));
defined('CRLF') ?: define('CRLF', CR . LF);
defined('TAB') ?: define('TAB', chr(9));
defined('NUL') ?: define('NUL', chr(0));
defined('SUB') ?: define('SUB', chr(26));

require __DIR__ . '/../.Build/vendor/autoload.php';
