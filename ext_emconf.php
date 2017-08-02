<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "t3events".
 *
 * Auto generated 02-08-2017 13:19
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array (
	'title' => 'Events',
	'description' => 'Manage events, show teasers, list and single views.',
	'category' => 'plugin',
	'version' => '0.33.3',
	'state' => 'beta',
	'uploadfolder' => 1,
	'createDirs' => '',
	'clearcacheonload' => 0,
	'author' => 'Dirk Wenzel, Michael Kasten',
	'author_email' => 't3events@gmx.de, kasten@webfox01.de',
	'author_company' => 'Agentur Webfox GmbH, Consulting Piezunka Schamoni - Information Technologies GmbH',
	'constraints' => 
	array (
		'depends' => 
		array (
			'typo3' => '6.2.0-6.2.99',
			'php' => '5.5.0-0.0.0',
			't3calendar' => '0.4.1-0.0.0',
		),
		'conflicts' => 
		array (
		),
		'suggests' => 
		array (
		),
	),
	'_md5_values_when_last_written' => 'a:472:{s:9:"ChangeLog";s:4:"3dcc";s:9:"README.md";s:4:"cb0c";s:20:"class.ext_update.php";s:4:"7242";s:13:"composer.json";s:4:"6d42";s:21:"ext_conf_template.txt";s:4:"59cb";s:12:"ext_icon.gif";s:4:"fed2";s:17:"ext_localconf.php";s:4:"d272";s:14:"ext_tables.php";s:4:"7721";s:14:"ext_tables.sql";s:4:"cdc4";s:28:"ext_typoscript_constants.txt";s:4:"885b";s:24:"ext_typoscript_setup.txt";s:4:"b837";s:27:"Classes/CallStaticTrait.php";s:4:"e34a";s:41:"Classes/InvalidConfigurationException.php";s:4:"7199";s:36:"Classes/InvalidFileTypeException.php";s:4:"fa97";s:32:"Classes/MissingFileException.php";s:4:"8834";s:33:"Classes/PatternReplacingTrait.php";s:4:"6a2d";s:37:"Classes/ResourceNotFoundException.php";s:4:"93dd";s:38:"Classes/UnsupportedMethodException.php";s:4:"9436";s:44:"Classes/Command/CleanUpCommandController.php";s:4:"312d";s:41:"Classes/Command/TaskCommandController.php";s:4:"05fe";s:48:"Classes/Configuration/PeriodConstraintLegend.php";s:4:"d9c9";s:48:"Classes/Controller/AbstractBackendController.php";s:4:"9e0d";s:41:"Classes/Controller/AbstractController.php";s:4:"8e0a";s:46:"Classes/Controller/AudienceRepositoryTrait.php";s:4:"9a9b";s:46:"Classes/Controller/CategoryRepositoryTrait.php";s:4:"b711";s:45:"Classes/Controller/CompanyRepositoryTrait.php";s:4:"3080";s:34:"Classes/Controller/DemandTrait.php";s:4:"bfc9";s:36:"Classes/Controller/DownloadTrait.php";s:4:"4acc";s:49:"Classes/Controller/EntityNotFoundHandlerTrait.php";s:4:"572e";s:38:"Classes/Controller/EventController.php";s:4:"aaf3";s:46:"Classes/Controller/EventDemandFactoryTrait.php";s:4:"9a0d";s:51:"Classes/Controller/EventLocationRepositoryTrait.php";s:4:"90a4";s:43:"Classes/Controller/EventRepositoryTrait.php";s:4:"55fe";s:47:"Classes/Controller/EventTypeRepositoryTrait.php";s:4:"ea0e";s:52:"Classes/Controller/FilterableControllerInterface.php";s:4:"2f1c";s:48:"Classes/Controller/FilterableControllerTrait.php";s:4:"06be";s:40:"Classes/Controller/FlashMessageTrait.php";s:4:"98d2";s:43:"Classes/Controller/GenreRepositoryTrait.php";s:4:"c2d3";s:38:"Classes/Controller/ModuleDataTrait.php";s:4:"4321";s:50:"Classes/Controller/NotificationRepositoryTrait.php";s:4:"4e61";s:44:"Classes/Controller/PerformanceController.php";s:4:"7ccc";s:52:"Classes/Controller/PerformanceDemandFactoryTrait.php";s:4:"9ac1";s:49:"Classes/Controller/PerformanceRepositoryTrait.php";s:4:"7a42";s:46:"Classes/Controller/PersistenceManagerTrait.php";s:4:"d694";s:47:"Classes/Controller/PersonDemandFactoryTrait.php";s:4:"9ce8";s:50:"Classes/Controller/RoutableControllerInterface.php";s:4:"c1f5";s:35:"Classes/Controller/RoutingTrait.php";s:4:"74ae";s:34:"Classes/Controller/SearchTrait.php";s:4:"7b22";s:35:"Classes/Controller/SessionTrait.php";s:4:"f60c";s:43:"Classes/Controller/SettingsUtilityTrait.php";s:4:"0769";s:38:"Classes/Controller/SignalInterface.php";s:4:"a8c1";s:34:"Classes/Controller/SignalTrait.php";s:4:"9601";s:42:"Classes/Controller/TaskRepositoryTrait.php";s:4:"148d";s:37:"Classes/Controller/TranslateTrait.php";s:4:"95a6";s:43:"Classes/Controller/VenueRepositoryTrait.php";s:4:"bdfe";s:46:"Classes/Controller/Backend/EventController.php";s:4:"9b3f";s:49:"Classes/Controller/Backend/ScheduleController.php";s:4:"e447";s:36:"Classes/Controller/Routing/Route.php";s:4:"892c";s:37:"Classes/Controller/Routing/Router.php";s:4:"b41e";s:46:"Classes/Controller/Routing/RouterInterface.php";s:4:"0fa3";s:57:"Classes/DataProvider/Form/EventPluginFormDataProvider.php";s:4:"a505";s:58:"Classes/DataProvider/Legend/AbstractPeriodDataProvider.php";s:4:"6e06";s:66:"Classes/DataProvider/Legend/LayeredLegendDataProviderInterface.php";s:4:"1acb";s:53:"Classes/DataProvider/Legend/PeriodAllDataProvider.php";s:4:"e023";s:57:"Classes/DataProvider/Legend/PeriodDataProviderFactory.php";s:4:"9df4";s:56:"Classes/DataProvider/Legend/PeriodFutureDataProvider.php";s:4:"be32";s:54:"Classes/DataProvider/Legend/PeriodPastDataProvider.php";s:4:"5390";s:58:"Classes/DataProvider/Legend/PeriodSpecificDataProvider.php";s:4:"3938";s:57:"Classes/DataProvider/Legend/PeriodUnknownDataProvider.php";s:4:"f4eb";s:69:"Classes/DataProvider/RouteLoader/RouteLoaderDataProviderInterface.php";s:4:"e17d";s:52:"Classes/Domain/Factory/Dto/AbstractDemandFactory.php";s:4:"7af5";s:53:"Classes/Domain/Factory/Dto/DemandFactoryInterface.php";s:4:"a68e";s:49:"Classes/Domain/Factory/Dto/EventDemandFactory.php";s:4:"070d";s:47:"Classes/Domain/Factory/Dto/MapPropertyTrait.php";s:4:"91d6";s:55:"Classes/Domain/Factory/Dto/PerformanceDemandFactory.php";s:4:"d2d6";s:60:"Classes/Domain/Factory/Dto/PeriodAwareDemandFactoryTrait.php";s:4:"bfaf";s:50:"Classes/Domain/Factory/Dto/PersonDemandFactory.php";s:4:"ba0c";s:48:"Classes/Domain/Factory/Dto/SkipPropertyTrait.php";s:4:"a765";s:37:"Classes/Domain/Model/AddressTrait.php";s:4:"b8df";s:33:"Classes/Domain/Model/Audience.php";s:4:"0a10";s:43:"Classes/Domain/Model/CategorizableTrait.php";s:4:"9ea0";s:33:"Classes/Domain/Model/Category.php";s:4:"77db";s:32:"Classes/Domain/Model/Company.php";s:4:"6840";s:32:"Classes/Domain/Model/Content.php";s:4:"e412";s:30:"Classes/Domain/Model/Event.php";s:4:"4db5";s:38:"Classes/Domain/Model/EventLocation.php";s:4:"6202";s:34:"Classes/Domain/Model/EventType.php";s:4:"3d3d";s:30:"Classes/Domain/Model/Genre.php";s:4:"9f52";s:43:"Classes/Domain/Model/GeoCodingInterface.php";s:4:"d16f";s:37:"Classes/Domain/Model/Notification.php";s:4:"8de4";s:34:"Classes/Domain/Model/Organizer.php";s:4:"2c02";s:36:"Classes/Domain/Model/Performance.php";s:4:"2985";s:42:"Classes/Domain/Model/PerformanceStatus.php";s:4:"f2dc";s:31:"Classes/Domain/Model/Person.php";s:4:"b481";s:35:"Classes/Domain/Model/PersonType.php";s:4:"eb52";s:29:"Classes/Domain/Model/Task.php";s:4:"90c9";s:36:"Classes/Domain/Model/TicketClass.php";s:4:"9daa";s:30:"Classes/Domain/Model/Venue.php";s:4:"5701";s:43:"Classes/Domain/Model/Dto/AbstractDemand.php";s:4:"3700";s:57:"Classes/Domain/Model/Dto/AudienceAwareDemandInterface.php";s:4:"4395";s:53:"Classes/Domain/Model/Dto/AudienceAwareDemandTrait.php";s:4:"8352";s:57:"Classes/Domain/Model/Dto/CategoryAwareDemandInterface.php";s:4:"1430";s:53:"Classes/Domain/Model/Dto/CategoryAwareDemandTrait.php";s:4:"ef07";s:44:"Classes/Domain/Model/Dto/DemandInterface.php";s:4:"1d10";s:44:"Classes/Domain/Model/Dto/EmConfiguration.php";s:4:"49d1";s:40:"Classes/Domain/Model/Dto/EventDemand.php";s:4:"83ce";s:62:"Classes/Domain/Model/Dto/EventLocationAwareDemandInterface.php";s:4:"f76b";s:58:"Classes/Domain/Model/Dto/EventLocationAwareDemandTrait.php";s:4:"a24b";s:58:"Classes/Domain/Model/Dto/EventTypeAwareDemandInterface.php";s:4:"d430";s:54:"Classes/Domain/Model/Dto/EventTypeAwareDemandTrait.php";s:4:"6afa";s:54:"Classes/Domain/Model/Dto/GenreAwareDemandInterface.php";s:4:"8f52";s:50:"Classes/Domain/Model/Dto/GenreAwareDemandTrait.php";s:4:"52c6";s:51:"Classes/Domain/Model/Dto/LocationAwareInterface.php";s:4:"0633";s:47:"Classes/Domain/Model/Dto/LocationAwareTrait.php";s:4:"560d";s:39:"Classes/Domain/Model/Dto/ModuleData.php";s:4:"28bb";s:54:"Classes/Domain/Model/Dto/OrderAwareDemandInterface.php";s:4:"109f";s:50:"Classes/Domain/Model/Dto/OrderAwareDemandTrait.php";s:4:"3870";s:46:"Classes/Domain/Model/Dto/PerformanceDemand.php";s:4:"b591";s:55:"Classes/Domain/Model/Dto/PeriodAwareDemandInterface.php";s:4:"3b97";s:51:"Classes/Domain/Model/Dto/PeriodAwareDemandTrait.php";s:4:"523d";s:41:"Classes/Domain/Model/Dto/PersonDemand.php";s:4:"8b1b";s:35:"Classes/Domain/Model/Dto/Search.php";s:4:"3e2c";s:55:"Classes/Domain/Model/Dto/SearchAwareDemandInterface.php";s:4:"1ed6";s:51:"Classes/Domain/Model/Dto/SearchAwareDemandTrait.php";s:4:"2e9c";s:42:"Classes/Domain/Model/Dto/SearchFactory.php";s:4:"1923";s:55:"Classes/Domain/Model/Dto/StatusAwareDemandInterface.php";s:4:"314e";s:51:"Classes/Domain/Model/Dto/StatusAwareDemandTrait.php";s:4:"213b";s:54:"Classes/Domain/Model/Dto/VenueAwareDemandInterface.php";s:4:"4227";s:50:"Classes/Domain/Model/Dto/VenueAwareDemandTrait.php";s:4:"db60";s:56:"Classes/Domain/Repository/AbstractDemandedRepository.php";s:4:"b3ba";s:67:"Classes/Domain/Repository/AudienceConstraintRepositoryInterface.php";s:4:"cb45";s:63:"Classes/Domain/Repository/AudienceConstraintRepositoryTrait.php";s:4:"1cc4";s:48:"Classes/Domain/Repository/AudienceRepository.php";s:4:"026b";s:67:"Classes/Domain/Repository/CategoryConstraintRepositoryInterface.php";s:4:"3a50";s:63:"Classes/Domain/Repository/CategoryConstraintRepositoryTrait.php";s:4:"b31f";s:48:"Classes/Domain/Repository/CategoryRepository.php";s:4:"1a48";s:47:"Classes/Domain/Repository/CompanyRepository.php";s:4:"a850";s:57:"Classes/Domain/Repository/DemandedRepositoryInterface.php";s:4:"6eef";s:53:"Classes/Domain/Repository/DemandedRepositoryTrait.php";s:4:"510b";s:53:"Classes/Domain/Repository/EventLocationRepository.php";s:4:"7096";s:45:"Classes/Domain/Repository/EventRepository.php";s:4:"3e70";s:68:"Classes/Domain/Repository/EventTypeConstraintRepositoryInterface.php";s:4:"3ac6";s:64:"Classes/Domain/Repository/EventTypeConstraintRepositoryTrait.php";s:4:"8c70";s:49:"Classes/Domain/Repository/EventTypeRepository.php";s:4:"8f03";s:64:"Classes/Domain/Repository/GenreConstraintRepositoryInterface.php";s:4:"493d";s:60:"Classes/Domain/Repository/GenreConstraintRepositoryTrait.php";s:4:"1d05";s:45:"Classes/Domain/Repository/GenreRepository.php";s:4:"b271";s:67:"Classes/Domain/Repository/LocationConstraintRepositoryInterface.php";s:4:"cdf4";s:63:"Classes/Domain/Repository/LocationConstraintRepositoryTrait.php";s:4:"7240";s:52:"Classes/Domain/Repository/NotificationRepository.php";s:4:"3f02";s:51:"Classes/Domain/Repository/PerformanceRepository.php";s:4:"77cd";s:65:"Classes/Domain/Repository/PeriodConstraintRepositoryInterface.php";s:4:"2130";s:61:"Classes/Domain/Repository/PeriodConstraintRepositoryTrait.php";s:4:"2473";s:46:"Classes/Domain/Repository/PersonRepository.php";s:4:"4e4b";s:65:"Classes/Domain/Repository/StatusConstraintRepositoryInterface.php";s:4:"4ebc";s:61:"Classes/Domain/Repository/StatusConstraintRepositoryTrait.php";s:4:"90ad";s:44:"Classes/Domain/Repository/TaskRepository.php";s:4:"6fb2";s:53:"Classes/Domain/Repository/TaskRepositoryInterface.php";s:4:"5a4d";s:64:"Classes/Domain/Repository/VenueConstraintRepositoryInterface.php";s:4:"e385";s:60:"Classes/Domain/Repository/VenueConstraintRepositoryTrait.php";s:4:"35b9";s:45:"Classes/Domain/Repository/VenueRepository.php";s:4:"ae16";s:32:"Classes/Hooks/BackendUtility.php";s:4:"9aed";s:31:"Classes/Hooks/ItemsProcFunc.php";s:4:"f3de";s:36:"Classes/Resource/ResourceFactory.php";s:4:"0923";s:32:"Classes/Resource/VectorImage.php";s:4:"322d";s:36:"Classes/Service/ExtensionService.php";s:4:"b829";s:44:"Classes/Service/ModuleDataStorageService.php";s:4:"279d";s:39:"Classes/Service/NotificationService.php";s:4:"4326";s:31:"Classes/Service/RouteLoader.php";s:4:"0a95";s:43:"Classes/Session/NamespaceAwareInterface.php";s:4:"af84";s:36:"Classes/Session/SessionInterface.php";s:4:"9b92";s:39:"Classes/Session/Typo3BackendSession.php";s:4:"a067";s:32:"Classes/Session/Typo3Session.php";s:4:"df25";s:39:"Classes/Update/MigratePluginRecords.php";s:4:"a3bc";s:37:"Classes/Update/MigrateTaskRecords.php";s:4:"8012";s:42:"Classes/Utility/EmConfigurationUtility.php";s:4:"a6bf";s:28:"Classes/Utility/GeoCoder.php";s:4:"9b20";s:35:"Classes/Utility/SettingsUtility.php";s:4:"053f";s:38:"Classes/Utility/TableConfiguration.php";s:4:"e031";s:41:"Classes/Utility/TemplateLayoutUtility.php";s:4:"c23d";s:26:"Classes/View/IcalTrait.php";s:4:"aba6";s:31:"Classes/View/Event/ListIcal.php";s:4:"7fd2";s:31:"Classes/View/Event/ShowIcal.php";s:4:"7a3a";s:37:"Classes/View/Performance/ListIcal.php";s:4:"ac09";s:37:"Classes/View/Performance/ShowIcal.php";s:4:"c55c";s:44:"Classes/ViewHelpers/FindEventsViewHelper.php";s:4:"b2d8";s:44:"Classes/ViewHelpers/HeaderDataViewHelper.php";s:4:"0aab";s:41:"Classes/ViewHelpers/MetaTagViewHelper.php";s:4:"0165";s:42:"Classes/ViewHelpers/TitleTagViewHelper.php";s:4:"3ca5";s:47:"Classes/ViewHelpers/Be/EditRecordViewHelper.php";s:4:"4520";s:52:"Classes/ViewHelpers/Event/PerformancesViewHelper.php";s:4:"a7c9";s:51:"Classes/ViewHelpers/Format/ArrayToCsvViewHelper.php";s:4:"d770";s:45:"Classes/ViewHelpers/Format/DateViewHelper.php";s:4:"7ef6";s:45:"Classes/ViewHelpers/Format/TrimViewHelper.php";s:4:"0ecb";s:56:"Classes/ViewHelpers/Format/Event/DateRangeViewHelper.php";s:4:"a520";s:62:"Classes/ViewHelpers/Format/Performance/DateRangeViewHelper.php";s:4:"ee31";s:48:"Classes/ViewHelpers/Location/CountViewHelper.php";s:4:"f3b1";s:49:"Classes/ViewHelpers/Location/UniqueViewHelper.php";s:4:"bd5d";s:43:"Configuration/FlexForms/flexform_events.xml";s:4:"06cd";s:55:"Configuration/TCA/tx_t3events_domain_model_audience.php";s:4:"fb6a";s:54:"Configuration/TCA/tx_t3events_domain_model_company.php";s:4:"865c";s:52:"Configuration/TCA/tx_t3events_domain_model_event.php";s:4:"2288";s:60:"Configuration/TCA/tx_t3events_domain_model_eventlocation.php";s:4:"c667";s:56:"Configuration/TCA/tx_t3events_domain_model_eventtype.php";s:4:"0232";s:52:"Configuration/TCA/tx_t3events_domain_model_genre.php";s:4:"d5df";s:59:"Configuration/TCA/tx_t3events_domain_model_notification.php";s:4:"28da";s:56:"Configuration/TCA/tx_t3events_domain_model_organizer.php";s:4:"5d9f";s:58:"Configuration/TCA/tx_t3events_domain_model_performance.php";s:4:"8945";s:64:"Configuration/TCA/tx_t3events_domain_model_performancestatus.php";s:4:"f059";s:53:"Configuration/TCA/tx_t3events_domain_model_person.php";s:4:"cafe";s:57:"Configuration/TCA/tx_t3events_domain_model_persontype.php";s:4:"c3ef";s:51:"Configuration/TCA/tx_t3events_domain_model_task.php";s:4:"9681";s:58:"Configuration/TCA/tx_t3events_domain_model_ticketclass.php";s:4:"f7c9";s:52:"Configuration/TCA/tx_t3events_domain_model_venue.php";s:4:"74da";s:62:"Configuration/TCA/Overrides/tx_t3events_domain_model_event.php";s:4:"d055";s:38:"Configuration/TSConfig/PageTSConfig.ts";s:4:"79df";s:38:"Configuration/TypoScript/constants.txt";s:4:"4999";s:34:"Configuration/TypoScript/setup.txt";s:4:"6b85";s:23:"Documentation/Index.rst";s:4:"5a29";s:22:"Documentation/Makefile";s:4:"c4b9";s:26:"Documentation/Settings.yml";s:4:"f946";s:21:"Documentation/conf.py";s:4:"1578";s:35:"Documentation/visibility_matrix.ods";s:4:"55c1";s:51:"Documentation/AdministratorsManual/UpdateScripts.md";s:4:"9170";s:37:"Documentation/Configuration/Index.rst";s:4:"9c14";s:40:"Documentation/Configuration/Templates.md";s:4:"fcb1";s:46:"Documentation/Configuration/Overview/Index.rst";s:4:"e31a";s:32:"Documentation/Credits/Images.txt";s:4:"ed16";s:31:"Documentation/Credits/Index.rst";s:4:"f7dc";s:28:"Documentation/HowTo/HowTo.md";s:4:"2ec0";s:39:"Documentation/Images/ComboViewMonth.png";s:4:"2173";s:38:"Documentation/Images/ComboViewYear.png";s:4:"805f";s:34:"Documentation/Images/MiniMonth.png";s:4:"a7ea";s:38:"Documentation/Images/createRecords.png";s:4:"f69d";s:41:"Documentation/Images/eventExtendedTab.png";s:4:"f491";s:40:"Documentation/Images/eventGeneralTab.png";s:4:"b2d8";s:36:"Documentation/Introduction/Index.rst";s:4:"1c16";s:49:"Documentation/Introduction/WhatDoesItDo/Index.rst";s:4:"f3fc";s:33:"Documentation/To-doList/Index.rst";s:4:"5048";s:35:"Documentation/UsersManual/Index.rst";s:4:"6857";s:52:"Documentation/UsersManual/AuxiliaryRecords/Index.rst";s:4:"34b3";s:43:"Documentation/UsersManual/Events/Images.txt";s:4:"88f5";s:42:"Documentation/UsersManual/Events/Index.rst";s:4:"ce1a";s:49:"Documentation/UsersManual/InsertPlugins/Index.rst";s:4:"153b";s:45:"Documentation/UsersManual/Overview/Images.txt";s:4:"5ade";s:44:"Documentation/UsersManual/Overview/Index.rst";s:4:"439c";s:40:"Documentation/ViewHelpers/ViewHelpers.md";s:4:"f4ff";s:56:"Documentation/ViewHelpers/Format/ArrayToCsvViewHelper.md";s:4:"6b6d";s:53:"Documentation/ViewHelpers/Location/CountViewHelper.md";s:4:"873a";s:54:"Documentation/ViewHelpers/Location/UniqueViewHelper.md";s:4:"2f08";s:43:"Resources/Private/Language/de.locallang.xlf";s:4:"578d";s:46:"Resources/Private/Language/de.locallang_db.xlf";s:4:"2824";s:46:"Resources/Private/Language/de.locallang_m1.xlf";s:4:"8351";s:46:"Resources/Private/Language/de.locallang_m2.xlf";s:4:"5691";s:52:"Resources/Private/Language/de.locallang_mod_main.xlf";s:4:"d7bc";s:43:"Resources/Private/Language/fr.locallang.xlf";s:4:"fdb2";s:40:"Resources/Private/Language/locallang.xlf";s:4:"893c";s:43:"Resources/Private/Language/locallang_be.xml";s:4:"4824";s:53:"Resources/Private/Language/locallang_csh_flexform.xml";s:4:"a11e";s:61:"Resources/Private/Language/locallang_csh_static_countries.xml";s:4:"b0fc";s:78:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_audience.xlf";s:4:"c493";s:77:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_company.xlf";s:4:"74ff";s:75:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_event.xml";s:4:"c260";s:83:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventlocation.xml";s:4:"7480";s:79:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_eventtype.xml";s:4:"6b98";s:75:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_genre.xml";s:4:"a338";s:78:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_location.xml";s:4:"6dda";s:79:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_organizer.xml";s:4:"bd02";s:81:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performance.xml";s:4:"5d6b";s:87:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_performancestatus.xml";s:4:"2fb4";s:74:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_task.xml";s:4:"08b9";s:81:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_ticketclass.xml";s:4:"c0b4";s:75:"Resources/Private/Language/locallang_csh_tx_t3events_domain_model_venue.xml";s:4:"139d";s:43:"Resources/Private/Language/locallang_db.xlf";s:4:"37d6";s:43:"Resources/Private/Language/locallang_m1.xlf";s:4:"95b4";s:43:"Resources/Private/Language/locallang_m2.xlf";s:4:"6af1";s:49:"Resources/Private/Language/locallang_mod_main.xlf";s:4:"d4c9";s:38:"Resources/Private/Layouts/Default.html";s:4:"262e";s:46:"Resources/Private/Layouts/Backend/Default.html";s:4:"6b08";s:50:"Resources/Private/Partials/Backend/FormErrors.html";s:4:"4d63";s:57:"Resources/Private/Partials/Backend/Common/SearchForm.html";s:4:"fb66";s:60:"Resources/Private/Partials/Backend/Common/SearchSubject.html";s:4:"5efd";s:51:"Resources/Private/Partials/Event/ICalendarItem.html";s:4:"233a";s:46:"Resources/Private/Partials/Event/ListItem.html";s:4:"6b8c";s:48:"Resources/Private/Partials/Event/Properties.html";s:4:"5e7f";s:48:"Resources/Private/Partials/Event/SingleItem.html";s:4:"21f1";s:50:"Resources/Private/Partials/EventLocation/Item.html";s:4:"4c9c";s:56:"Resources/Private/Partials/EventLocation/Properties.html";s:4:"e8d0";s:51:"Resources/Private/Partials/Location/Properties.html";s:4:"b5ff";s:46:"Resources/Private/Partials/Organizer/Item.html";s:4:"8487";s:52:"Resources/Private/Partials/Organizer/Properties.html";s:4:"4f90";s:57:"Resources/Private/Partials/Performance/ICalendarItem.html";s:4:"ecad";s:48:"Resources/Private/Partials/Performance/Item.html";s:4:"cfe2";s:52:"Resources/Private/Partials/Performance/ListItem.html";s:4:"b3bc";s:57:"Resources/Private/Partials/Performance/ListItemShort.html";s:4:"30ee";s:54:"Resources/Private/Partials/Performance/Properties.html";s:4:"6a49";s:49:"Resources/Private/Partials/Person/Properties.html";s:4:"41df";s:48:"Resources/Private/Partials/TicketClass/Item.html";s:4:"38cd";s:48:"Resources/Private/Partials/TicketClass/List.html";s:4:"7e92";s:54:"Resources/Private/Partials/TicketClass/Properties.html";s:4:"e335";s:48:"Resources/Private/Partials/Venue/Properties.html";s:4:"34dc";s:51:"Resources/Private/Templates/Backend/Event/List.html";s:4:"b016";s:54:"Resources/Private/Templates/Backend/Schedule/List.html";s:4:"bf4d";s:43:"Resources/Private/Templates/Event/List.html";s:4:"e534";s:43:"Resources/Private/Templates/Event/List.ical";s:4:"d640";s:48:"Resources/Private/Templates/Event/QuickMenu.html";s:4:"8e9a";s:43:"Resources/Private/Templates/Event/Show.html";s:4:"81fb";s:43:"Resources/Private/Templates/Event/Show.ical";s:4:"4ee7";s:53:"Resources/Private/Templates/Performance/Calendar.html";s:4:"b3f8";s:49:"Resources/Private/Templates/Performance/List.html";s:4:"980c";s:49:"Resources/Private/Templates/Performance/List.ical";s:4:"fcdf";s:54:"Resources/Private/Templates/Performance/QuickMenu.html";s:4:"0cc0";s:49:"Resources/Private/Templates/Performance/Show.html";s:4:"c71b";s:49:"Resources/Private/Templates/Performance/Show.ical";s:4:"aeb4";s:49:"Resources/Private/Templates/TicketClass/List.html";s:4:"d2a8";s:49:"Resources/Private/Templates/TicketClass/Show.html";s:4:"b45c";s:43:"Resources/Private/Templates/Venue/List.html";s:4:"aabc";s:43:"Resources/Private/Templates/Venue/Show.html";s:4:"ce43";s:66:"Resources/Private/Templates/ViewHelpers/Widget/Paginate/Index.html";s:4:"556c";s:32:"Resources/Public/Css/backend.css";s:4:"388c";s:30:"Resources/Public/Css/forms.css";s:4:"0c90";s:31:"Resources/Public/Css/styles.css";s:4:"ffb3";s:38:"Resources/Public/Css/t3eventsBasic.css";s:4:"4cf4";s:47:"Resources/Public/Css/t3eventsBasic.original.css";s:4:"40e0";s:40:"Resources/Public/Icons/calendar-blue.svg";s:4:"7e75";s:35:"Resources/Public/Icons/calendar.svg";s:4:"db63";s:48:"Resources/Public/Icons/event-calendar-symbol.svg";s:4:"1ab5";s:41:"Resources/Public/Icons/event-calendar.svg";s:4:"31a5";s:47:"Resources/Public/Icons/module_icon_schedule.png";s:4:"c547";s:35:"Resources/Public/Icons/relation.gif";s:4:"e615";s:43:"Resources/Public/Icons/static_countries.gif";s:4:"1103";s:60:"Resources/Public/Icons/tx_t3events_domain_model_audience.gif";s:4:"1103";s:59:"Resources/Public/Icons/tx_t3events_domain_model_company.gif";s:4:"905a";s:57:"Resources/Public/Icons/tx_t3events_domain_model_event.gif";s:4:"c924";s:65:"Resources/Public/Icons/tx_t3events_domain_model_eventlocation.gif";s:4:"2959";s:61:"Resources/Public/Icons/tx_t3events_domain_model_eventtype.gif";s:4:"c055";s:57:"Resources/Public/Icons/tx_t3events_domain_model_genre.gif";s:4:"5bb5";s:60:"Resources/Public/Icons/tx_t3events_domain_model_location.gif";s:4:"2959";s:64:"Resources/Public/Icons/tx_t3events_domain_model_notification.gif";s:4:"1103";s:61:"Resources/Public/Icons/tx_t3events_domain_model_organizer.gif";s:4:"3e33";s:63:"Resources/Public/Icons/tx_t3events_domain_model_performance.gif";s:4:"ff9d";s:69:"Resources/Public/Icons/tx_t3events_domain_model_performancestatus.gif";s:4:"5adf";s:58:"Resources/Public/Icons/tx_t3events_domain_model_person.png";s:4:"a8b9";s:62:"Resources/Public/Icons/tx_t3events_domain_model_persontype.png";s:4:"cac1";s:56:"Resources/Public/Icons/tx_t3events_domain_model_task.png";s:4:"136e";s:58:"Resources/Public/Icons/tx_t3events_domain_model_teaser.gif";s:4:"acae";s:63:"Resources/Public/Icons/tx_t3events_domain_model_ticketclass.gif";s:4:"06be";s:57:"Resources/Public/Icons/tx_t3events_domain_model_venue.gif";s:4:"4934";s:39:"Resources/Public/Images/dummy-image.png";s:4:"8084";s:46:"Resources/Public/Images/period_constraints.svg";s:4:"7f41";s:40:"Resources/Public/JavaScript/accordion.js";s:4:"49d0";s:39:"Resources/Public/JavaScript/calendar.js";s:4:"849f";s:43:"Resources/Public/JavaScript/jquery-2.1.4.js";s:4:"d64c";s:25:"Tests/Build/UnitTests.xml";s:4:"8bdf";s:40:"Tests/Unit/PatternReplacingTraitTest.php";s:4:"d493";s:51:"Tests/Unit/Command/CleanUpCommandControllerTest.php";s:4:"7746";s:48:"Tests/Unit/Command/TaskCommandControllerTest.php";s:4:"b7bc";s:55:"Tests/Unit/Configuration/PeriodConstraintLegendTest.php";s:4:"2604";s:55:"Tests/Unit/Controller/AbstractBackendControllerTest.php";s:4:"f34c";s:48:"Tests/Unit/Controller/AbstractControllerTest.php";s:4:"a4a0";s:53:"Tests/Unit/Controller/AudienceRepositoryTraitTest.php";s:4:"ba80";s:53:"Tests/Unit/Controller/CategoryRepositoryTraitTest.php";s:4:"7c37";s:52:"Tests/Unit/Controller/CompanyRepositoryTraitTest.php";s:4:"5f23";s:41:"Tests/Unit/Controller/DemandTraitTest.php";s:4:"7d7e";s:43:"Tests/Unit/Controller/DownloadTraitTest.php";s:4:"0b2b";s:56:"Tests/Unit/Controller/EntityNotFoundHandlerTraitTest.php";s:4:"f856";s:45:"Tests/Unit/Controller/EventControllerTest.php";s:4:"192a";s:53:"Tests/Unit/Controller/EventDemandFactoryTraitTest.php";s:4:"4000";s:58:"Tests/Unit/Controller/EventLocationRepositoryTraitTest.php";s:4:"9912";s:50:"Tests/Unit/Controller/EventRepositoryTraitTest.php";s:4:"28a8";s:54:"Tests/Unit/Controller/EventTypeRepositoryTraitTest.php";s:4:"4be1";s:55:"Tests/Unit/Controller/FilterableControllerTraitTest.php";s:4:"6577";s:47:"Tests/Unit/Controller/FlashMessageTraitTest.php";s:4:"c506";s:50:"Tests/Unit/Controller/GenreRepositoryTraitTest.php";s:4:"d839";s:45:"Tests/Unit/Controller/ModuleDataTraitTest.php";s:4:"3e21";s:57:"Tests/Unit/Controller/NotificationRepositoryTraitTest.php";s:4:"4ce3";s:51:"Tests/Unit/Controller/PerformanceControllerTest.php";s:4:"37fc";s:59:"Tests/Unit/Controller/PerformanceDemandFactoryTraitTest.php";s:4:"a564";s:56:"Tests/Unit/Controller/PerformanceRepositoryTraitTest.php";s:4:"ce25";s:53:"Tests/Unit/Controller/PersistenceManagerTraitTest.php";s:4:"bb32";s:54:"Tests/Unit/Controller/PersonDemandFactoryTraitTest.php";s:4:"6b40";s:42:"Tests/Unit/Controller/RoutingTraitTest.php";s:4:"c5b2";s:41:"Tests/Unit/Controller/SearchTraitTest.php";s:4:"0c82";s:42:"Tests/Unit/Controller/SessionTraitTest.php";s:4:"ccd1";s:50:"Tests/Unit/Controller/SettingsUtilityTraitTest.php";s:4:"bf23";s:49:"Tests/Unit/Controller/TaskRepositoryTraitTest.php";s:4:"0641";s:50:"Tests/Unit/Controller/VenueRepositoryTraitTest.php";s:4:"5134";s:53:"Tests/Unit/Controller/Backend/EventControllerTest.php";s:4:"486a";s:56:"Tests/Unit/Controller/Backend/ScheduleControllerTest.php";s:4:"bf01";s:43:"Tests/Unit/Controller/Routing/RouteTest.php";s:4:"0862";s:44:"Tests/Unit/Controller/Routing/RouterTest.php";s:4:"40e4";s:64:"Tests/Unit/DataProvider/Form/EventPluginFormDataProviderTest.php";s:4:"3dfa";s:65:"Tests/Unit/DataProvider/Legend/AbstractPeriodDataProviderTest.php";s:4:"d199";s:64:"Tests/Unit/DataProvider/Legend/PeriodDataProviderFactoryTest.php";s:4:"a18c";s:56:"Tests/Unit/Domain/Factory/Dto/EventDemandFactoryTest.php";s:4:"2d5f";s:62:"Tests/Unit/Domain/Factory/Dto/PerformanceDemandFactoryTest.php";s:4:"be8e";s:67:"Tests/Unit/Domain/Factory/Dto/PeriodAwareDemandFactoryTraitTest.php";s:4:"0b1a";s:57:"Tests/Unit/Domain/Factory/Dto/PersonDemandFactoryTest.php";s:4:"c761";s:44:"Tests/Unit/Domain/Model/AddressTraitTest.php";s:4:"1b3e";s:40:"Tests/Unit/Domain/Model/AudienceTest.php";s:4:"71c8";s:50:"Tests/Unit/Domain/Model/CategorizableTraitTest.php";s:4:"38f9";s:39:"Tests/Unit/Domain/Model/CompanyTest.php";s:4:"9495";s:39:"Tests/Unit/Domain/Model/ContentTest.php";s:4:"83d7";s:45:"Tests/Unit/Domain/Model/EventLocationTest.php";s:4:"9168";s:37:"Tests/Unit/Domain/Model/EventTest.php";s:4:"907e";s:41:"Tests/Unit/Domain/Model/EventTypeTest.php";s:4:"7799";s:37:"Tests/Unit/Domain/Model/GenreTest.php";s:4:"9e35";s:44:"Tests/Unit/Domain/Model/NotificationTest.php";s:4:"2114";s:41:"Tests/Unit/Domain/Model/OrganizerTest.php";s:4:"1c8e";s:49:"Tests/Unit/Domain/Model/PerformanceStatusTest.php";s:4:"b3f3";s:43:"Tests/Unit/Domain/Model/PerformanceTest.php";s:4:"a090";s:38:"Tests/Unit/Domain/Model/PersonTest.php";s:4:"85a3";s:42:"Tests/Unit/Domain/Model/PersonTypeTest.php";s:4:"cd5f";s:36:"Tests/Unit/Domain/Model/TaskTest.php";s:4:"6e9f";s:43:"Tests/Unit/Domain/Model/TicketClassTest.php";s:4:"0d49";s:37:"Tests/Unit/Domain/Model/VenueTest.php";s:4:"fa97";s:50:"Tests/Unit/Domain/Model/Dto/AbstractDemandTest.php";s:4:"cbb2";s:60:"Tests/Unit/Domain/Model/Dto/AudienceAwareDemandTraitTest.php";s:4:"899e";s:60:"Tests/Unit/Domain/Model/Dto/CategoryAwareDemandTraitTest.php";s:4:"e191";s:51:"Tests/Unit/Domain/Model/Dto/EmConfigurationTest.php";s:4:"93a1";s:47:"Tests/Unit/Domain/Model/Dto/EventDemandTest.php";s:4:"9c29";s:65:"Tests/Unit/Domain/Model/Dto/EventLocationAwareDemandTraitTest.php";s:4:"3b68";s:61:"Tests/Unit/Domain/Model/Dto/EventTypeAwareDemandTraitTest.php";s:4:"33d2";s:57:"Tests/Unit/Domain/Model/Dto/GenreAwareDemandTraitTest.php";s:4:"c4a1";s:54:"Tests/Unit/Domain/Model/Dto/LocationAwareTraitTest.php";s:4:"3942";s:46:"Tests/Unit/Domain/Model/Dto/ModuleDataTest.php";s:4:"3e29";s:57:"Tests/Unit/Domain/Model/Dto/OrderAwareDemandTraitTest.php";s:4:"52a5";s:53:"Tests/Unit/Domain/Model/Dto/PerformanceDemandTest.php";s:4:"fe6b";s:58:"Tests/Unit/Domain/Model/Dto/PeriodAwareDemandTraitTest.php";s:4:"247e";s:58:"Tests/Unit/Domain/Model/Dto/SearchAwareDemandTraitTest.php";s:4:"9a03";s:49:"Tests/Unit/Domain/Model/Dto/SearchFactoryTest.php";s:4:"8315";s:42:"Tests/Unit/Domain/Model/Dto/SearchTest.php";s:4:"f2e2";s:58:"Tests/Unit/Domain/Model/Dto/StatusAwareDemandTraitTest.php";s:4:"65e1";s:57:"Tests/Unit/Domain/Model/Dto/VenueAwareDemandTraitTest.php";s:4:"54c3";s:63:"Tests/Unit/Domain/Repository/AbstractDemandedRepositoryTest.php";s:4:"f105";s:70:"Tests/Unit/Domain/Repository/AudienceConstraintRepositoryTraitTest.php";s:4:"bd29";s:55:"Tests/Unit/Domain/Repository/AudienceRepositoryTest.php";s:4:"cdb0";s:70:"Tests/Unit/Domain/Repository/CategoryConstraintRepositoryTraitTest.php";s:4:"be6e";s:55:"Tests/Unit/Domain/Repository/CategoryRepositoryTest.php";s:4:"faa1";s:60:"Tests/Unit/Domain/Repository/DemandedRepositoryTraitTest.php";s:4:"7ad0";s:52:"Tests/Unit/Domain/Repository/EventRepositoryTest.php";s:4:"c469";s:71:"Tests/Unit/Domain/Repository/EventTypeConstraintRepositoryTraitTest.php";s:4:"8066";s:56:"Tests/Unit/Domain/Repository/EventTypeRepositoryTest.php";s:4:"91e6";s:67:"Tests/Unit/Domain/Repository/GenreConstraintRepositoryTraitTest.php";s:4:"a072";s:52:"Tests/Unit/Domain/Repository/GenreRepositoryTest.php";s:4:"3043";s:58:"Tests/Unit/Domain/Repository/PerformanceRepositoryTest.php";s:4:"ecf8";s:68:"Tests/Unit/Domain/Repository/PeriodConstraintRepositoryTraitTest.php";s:4:"a08f";s:53:"Tests/Unit/Domain/Repository/PersonRepositoryTest.php";s:4:"11b4";s:68:"Tests/Unit/Domain/Repository/StatusConstraintRepositoryTraitTest.php";s:4:"d1c1";s:51:"Tests/Unit/Domain/Repository/TaskRepositoryTest.php";s:4:"e6a4";s:67:"Tests/Unit/Domain/Repository/VenueConstraintRepositoryTraitTest.php";s:4:"e3af";s:52:"Tests/Unit/Domain/Repository/VenueRepositoryTest.php";s:4:"8a23";s:65:"Tests/Unit/Domain/Repository/Fixtures/FindDemandedHookFixture.php";s:4:"78e4";s:38:"Tests/Unit/Hooks/ItemsProcFuncTest.php";s:4:"4b12";s:43:"Tests/Unit/Resource/ResourceFactoryTest.php";s:4:"1811";s:39:"Tests/Unit/Resource/VectorImageTest.php";s:4:"693d";s:43:"Tests/Unit/Service/ExtensionServiceTest.php";s:4:"d208";s:51:"Tests/Unit/Service/ModuleDataStorageServiceTest.php";s:4:"0fe4";s:46:"Tests/Unit/Service/NotificationServiceTest.php";s:4:"1073";s:38:"Tests/Unit/Service/RouteLoaderTest.php";s:4:"e8eb";s:46:"Tests/Unit/Session/Typo3BackendSessionTest.php";s:4:"0c9b";s:39:"Tests/Unit/Session/Typo3SessionTest.php";s:4:"7f51";s:46:"Tests/Unit/Update/MigratePluginRecordsTest.php";s:4:"acd7";s:44:"Tests/Unit/Update/MigrateTaskRecordsTest.php";s:4:"9227";s:49:"Tests/Unit/Utility/EmConfigurationUtilityTest.php";s:4:"4751";s:42:"Tests/Unit/Utility/SettingsUtilityTest.php";s:4:"b054";s:45:"Tests/Unit/Utility/TableConfigurationTest.php";s:4:"11d8";s:48:"Tests/Unit/Utility/TemplateLayoutUtilityTest.php";s:4:"3b9a";s:33:"Tests/Unit/View/IcalTraitTest.php";s:4:"2b97";s:58:"Tests/Unit/ViewHelpers/Format/ArrayToCsvViewHelperTest.php";s:4:"1f70";s:55:"Tests/Unit/ViewHelpers/Location/CountViewHelperTest.php";s:4:"0ae8";s:56:"Tests/Unit/ViewHelpers/Location/UniqueViewHelperTest.php";s:4:"01e7";}',
);

