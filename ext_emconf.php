<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "t3events".
 *
 * Auto generated 26-01-2013 22:35
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Events',
	'description' => 'Manage events, show teasers, list and single views.',
	'category' => 'plugin',
	'author' => 'Dirk Wenzel, Michael Kasten',
	'author_email' => 'wenzel@webfox01.de, kasten@webfox01.de',
	'author_company' => 'Agentur Webfox, Agentur Webfox',
	'shy' => '',
	'priority' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'version' => '0.3.0',
	'constraints' => 
	array (
		'depends' => 
		array (
			'extbase' => '1.3',
			'fluid' => '1.3',
			'typo3' => '4.5-0.0.0',
			'static_info_tables' => '2.0.0',
			//'static_info_tables_extbase' => '1.1.0',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:130:{s:9:"ChangeLog";s:4:"0168";s:12:"ext_icon.gif";s:4:"e922";s:17:"ext_localconf.php";s:4:"c4e9";s:14:"ext_tables.php";s:4:"a574";s:14:"ext_tables.sql";s:4:"a186";s:24:"ext_typoscript_setup.txt";s:4:"5445";s:21:"ExtensionBuilder.json";s:4:"ea3f";s:38:"Classes/Controller/EventController.php";s:4:"1fb5";s:46:"Classes/Controller/EventLocationController.php";s:4:"f791";s:42:"Classes/Controller/OrganizerController.php";s:4:"331c";s:44:"Classes/Controller/PerformanceController.php";s:4:"d368";s:39:"Classes/Controller/TeaserController.php";s:4:"18f8";s:42:"Classes/Controller/TeaserController_dw.php";s:4:"4128";s:44:"Classes/Controller/TicketClassController.php";s:4:"ac45";s:38:"Classes/Controller/VenueController.php";s:4:"afa6";s:39:"Classes/Domain/Model/AbstractDemand.php";s:4:"372c";s:32:"Classes/Domain/Model/Country.php";s:4:"d6c7";s:30:"Classes/Domain/Model/Event.php";s:4:"5883";s:36:"Classes/Domain/Model/EventDemand.php";s:4:"0c7d";s:38:"Classes/Domain/Model/EventLocation.php";s:4:"ec3c";s:34:"Classes/Domain/Model/EventType.php";s:4:"fad3";s:30:"Classes/Domain/Model/Genre.php";s:4:"be37";s:34:"Classes/Domain/Model/Organizer.php";s:4:"043d";s:36:"Classes/Domain/Model/Performance.php";s:4:"e581";s:42:"Classes/Domain/Model/PerformanceStatus.php";s:4:"d43d";s:31:"Classes/Domain/Model/Teaser.php";s:4:"c211";s:37:"Classes/Domain/Model/TeaserDemand.php";s:4:"2239";s:36:"Classes/Domain/Model/TicketClass.php";s:4:"f154";s:30:"Classes/Domain/Model/Venue.php";s:4:"db60";s:53:"Classes/Domain/Repository/EventLocationRepository.php";s:4:"8eb5";s:45:"Classes/Domain/Repository/EventRepository.php";s:4:"63a0";s:45:"Classes/Domain/Repository/GenreRepository.php";s:4:"9c26";s:51:"Classes/Domain/Repository/PerformanceRepository.php";s:4:"d39b";s:46:"Classes/Domain/Repository/TeaserRepository.php";s:4:"62c7";s:52:"Classes/ViewHelpers/Event/PerformancesViewHelper.php";s:4:"1432";s:60:"Classes/ViewHelpers/Widget/Controller/PaginateController.php";s:4:"afaa";s:44:"Configuration/ExtensionBuilder/settings.yaml";s:4:"84ed";s:47:"Configuration/FlexForms/flexform_events.xml";s:4:"6ca6";s:29:"Configuration/TCA/Country.php";s:4:"c1d8";s:27:"Configuration/TCA/Event.php";s:4:"05b2";s:35:"Configuration/TCA/EventLocation.php";s:4:"297d";s:31:"Configuration/TCA/EventType.php";s:4:"dced";s:27:"Configuration/TCA/Genre.php";s:4:"cd59";s:31:"Configuration/TCA/Organizer.php";s:4:"ef23";s:33:"Configuration/TCA/Performance.php";s:4:"ba0a";s:39:"Configuration/TCA/PerformanceStatus.php";s:4:"431c";s:28:"Configuration/TCA/Teaser.php";s:4:"a713";s:33:"Configuration/TCA/TicketClass.php";s:4:"ef58";s:27:"Configuration/TCA/Venue.php";s:4:"81ae";s:38:"Configuration/TypoScript/constants.txt";s:4:"d9ed";s:34:"Configuration/TypoScript/setup.txt";s:4:"56e1";s:40:"Resources/Private/Language/locallang.xml";s:4:"8b54";s:61:"Resources/Private/Language/locallang_csh_static_countries.xml";s:4:"c19c";s:83:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_event.xml";s:4:"9f2d";s:91:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventlocation.xml";s:4:"29ca";s:87:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventtype.xml";s:4:"3bf0";s:83:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_genre.xml";s:4:"c390";s:86:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_location.xml";s:4:"7811";s:87:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_organizer.xml";s:4:"f65d";s:89:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performance.xml";s:4:"e0b0";s:95:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performancestatus.xml";s:4:"d78a";s:84:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_teaser.xml";s:4:"62f5";s:89:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_ticketclass.xml";s:4:"c1e7";s:83:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_venue.xml";s:4:"441f";s:43:"Resources/Private/Language/locallang_db.xml";s:4:"9862";s:38:"Resources/Private/Layouts/Default.html";s:4:"fb34";s:46:"Resources/Private/Partials/Event/ListItem.html";s:4:"a417";s:48:"Resources/Private/Partials/Event/Properties.html";s:4:"d475";s:48:"Resources/Private/Partials/Event/SingleItem.html";s:4:"5d2d";s:50:"Resources/Private/Partials/EventLocation/Item.html";s:4:"f3c7";s:56:"Resources/Private/Partials/EventLocation/Properties.html";s:4:"4e95";s:51:"Resources/Private/Partials/Location/Properties.html";s:4:"b018";s:46:"Resources/Private/Partials/Organizer/Item.html";s:4:"5b42";s:52:"Resources/Private/Partials/Organizer/Properties.html";s:4:"d337";s:48:"Resources/Private/Partials/Performance/Item.html";s:4:"8382";s:54:"Resources/Private/Partials/Performance/Properties.html";s:4:"5f19";s:43:"Resources/Private/Partials/Teaser/Item.html";s:4:"b2f4";s:48:"Resources/Private/Partials/TicketClass/Item.html";s:4:"59d7";s:48:"Resources/Private/Partials/TicketClass/List.html";s:4:"0e72";s:54:"Resources/Private/Partials/TicketClass/Properties.html";s:4:"2123";s:48:"Resources/Private/Partials/Venue/Properties.html";s:4:"8e0d";s:43:"Resources/Private/Templates/Event/List.html";s:4:"e635";s:48:"Resources/Private/Templates/Event/QuickMenu.html";s:4:"b774";s:43:"Resources/Private/Templates/Event/Show.html";s:4:"f69b";s:44:"Resources/Private/Templates/Teaser/List.html";s:4:"274e";s:49:"Resources/Private/Templates/TicketClass/List.html";s:4:"3b71";s:49:"Resources/Private/Templates/TicketClass/Show.html";s:4:"c532";s:43:"Resources/Private/Templates/Venue/List.html";s:4:"80d3";s:43:"Resources/Private/Templates/Venue/Show.html";s:4:"bbee";s:66:"Resources/Private/Templates/ViewHelpers/Widget/Paginate/Index.html";s:4:"8297";s:46:"Resources/Public/Css/t3eventsBasic.css";s:4:"5026";s:56:"Resources/Public/Css/t3eventsBasic2012-12-16.css";s:4:"4685";s:27:"Resources/Public/Css/tx.css";s:4:"55f0";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:43:"Resources/Public/Icons/static_countries.gif";s:4:"1103";s:65:"Resources/Public/Icons/tx_t3events_domain_model_event.gif";s:4:"905a";s:73:"Resources/Public/Icons/tx_t3events_domain_model_eventlocation.gif";s:4:"905a";s:69:"Resources/Public/Icons/tx_t3events_domain_model_eventtype.gif";s:4:"4e5b";s:65:"Resources/Public/Icons/tx_t3events_domain_model_genre.gif";s:4:"1103";s:68:"Resources/Public/Icons/tx_t3events_domain_model_location.gif";s:4:"1103";s:69:"Resources/Public/Icons/tx_t3events_domain_model_organizer.gif";s:4:"1103";s:71:"Resources/Public/Icons/tx_t3events_domain_model_performance.gif";s:4:"905a";s:77:"Resources/Public/Icons/tx_t3events_domain_model_performancestatus.gif";s:4:"1103";s:66:"Resources/Public/Icons/tx_t3events_domain_model_teaser.gif";s:4:"905a";s:71:"Resources/Public/Icons/tx_t3events_domain_model_ticketclass.gif";s:4:"1103";s:65:"Resources/Public/Icons/tx_t3events_domain_model_venue.gif";s:4:"1103";s:39:"Resources/Public/Images/dummy-image.png";s:4:"8084";s:32:"Resources/Public/Js/accordion.js";s:4:"b328";s:42:"Resources/Public/Js/accordion2012-12-16.js";s:4:"4296";s:45:"Tests/Unit/Controller/EventControllerTest.php";s:4:"6568";s:53:"Tests/Unit/Controller/EventLocationControllerTest.php";s:4:"dae5";s:49:"Tests/Unit/Controller/OrganizerControllerTest.php";s:4:"e8e3";s:51:"Tests/Unit/Controller/PerformanceControllerTest.php";s:4:"e081";s:46:"Tests/Unit/Controller/TeaserControllerTest.php";s:4:"5370";s:51:"Tests/Unit/Controller/TicketClassControllerTest.php";s:4:"81c3";s:45:"Tests/Unit/Controller/VenueControllerTest.php";s:4:"7cc1";s:46:"Tests/Unit/Domain/Model/AbstractDemandTest.php";s:4:"ef18";s:39:"Tests/Unit/Domain/Model/CountryTest.php";s:4:"4a7f";s:45:"Tests/Unit/Domain/Model/EventLocationTest.php";s:4:"5100";s:37:"Tests/Unit/Domain/Model/EventTest.php";s:4:"e594";s:41:"Tests/Unit/Domain/Model/EventTypeTest.php";s:4:"910e";s:37:"Tests/Unit/Domain/Model/GenreTest.php";s:4:"d52d";s:41:"Tests/Unit/Domain/Model/OrganizerTest.php";s:4:"c74e";s:49:"Tests/Unit/Domain/Model/PerformanceStatusTest.php";s:4:"a64c";s:43:"Tests/Unit/Domain/Model/PerformanceTest.php";s:4:"0559";s:44:"Tests/Unit/Domain/Model/TeaserDemandTest.php";s:4:"53f0";s:38:"Tests/Unit/Domain/Model/TeaserTest.php";s:4:"afbf";s:43:"Tests/Unit/Domain/Model/TicketClassTest.php";s:4:"80e7";s:37:"Tests/Unit/Domain/Model/VenueTest.php";s:4:"14a5";s:14:"doc/manual.sxw";s:4:"8d2d";}',
);

?>