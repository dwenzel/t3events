<?php
namespace Webfox\T3events\Tests\Controller;

use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use Webfox\T3events\Domain\Repository\AudienceRepository;
use Webfox\T3events\Domain\Repository\CompanyRepository;
use Webfox\T3events\Domain\Repository\EventTypeRepository;
use Webfox\T3events\Domain\Repository\GenreRepository;
use Webfox\T3events\Domain\Repository\VenueRepository;
use Webfox\T3events\InvalidFileTypeException;
use Webfox\T3events\Service\ModuleDataStorageService;
use TYPO3\CMS\Core\Tests\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Webfox\T3events\Controller\AbstractBackendController;
use Webfox\T3events\Domain\Model\Dto\ModuleData;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class AbstractBackendControllerTest extends UnitTestCase {

	/**
	 * @var AbstractBackendController
	 */
	protected $subject;

	/**
	 * set up
	 */
	protected function setUp() {
		$this->subject = $this->getAccessibleMock(
			AbstractBackendController::class, ['dummy'], [], '', false
		);
	}

	protected function mockObjectManager() {
		$mockObjectManager = $this->getMock(
			ObjectManager::class, ['get']
		);
		$this->inject($this->subject, 'objectManager', $mockObjectManager);

		return $mockObjectManager;
	}

	protected function mockModuleDataStorageService() {
		$mockService = $this->getMock(
			ModuleDataStorageService::class, ['persistModuleData', 'loadModuleData']
		);
		$this->subject->injectModuleDataStorageService($mockService);

		return $mockService;
	}

	/**
	 * @test
	 */
	public function resetActionResetsModuleDataAndForwardsToListAction() {
		$this->subject = $this->getAccessibleMock(
			AbstractBackendController::class, ['forward'], [], '', false
		);
		$moduleKey = 'bar';
		$GLOBALS['moduleName'] = $moduleKey;

		$moduleDataBeforeReset = new ModuleData();
		$moduleDataBeforeReset->setOverwriteDemand(['foo']);
		$mockModuleData = new ModuleData();
		$this->subject->_set('moduleData', $moduleDataBeforeReset);

		$mockObjectManager = $this->mockObjectManager();
		$mockObjectManager->expects($this->once())
			->method('get')
			->with(ModuleData::class)
			->will($this->returnValue($mockModuleData));

		$mockModuleDataService = $this->mockModuleDataStorageService();
		$mockModuleDataService->expects($this->once())
			->method('persistModuleData')
			->with($mockModuleData, $moduleKey);

		$this->subject->expects($this->once())
			->method('forward')
			->with('list');

		$this->subject->resetAction();
	}

	/**
	 * @test
	 */
	public function initializeActionSetsPageUidFromGlobalGetVar() {
		$_GET['id'] = '5';

		$this->subject->initializeAction();

		$this->assertSame(
			5,
			$this->subject->_get('pageUid')
		);
	}

	/**
	 * @test
	 */
	public function moduleDataStorageServiceCanBeInjected() {
		$mockService = $this->getMock(
			ModuleDataStorageService::class
		);

		$this->subject->injectModuleDataStorageService($mockService);
		$this->assertAttributeSame(
			$mockService,
			'moduleDataStorageService',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function eventTypeRepositoryCanBeInjected() {
		$mockRepository = $this->getMock(
			EventTypeRepository::class, [], [], '', false
		);

		$this->subject->injectEventTypeRepository($mockRepository);

		$this->assertAttributeSame(
			$mockRepository,
			'eventTypeRepository',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function companyRepositoryCanBeInjected() {
		$mockRepository = $this->getMock(
			CompanyRepository::class, [], [], '', false
		);

		$this->subject->injectCompanyRepository($mockRepository);

		$this->assertAttributeSame(
			$mockRepository,
			'companyRepository',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function genreRepositoryCanBeInjected() {
		$mockRepository = $this->getMock(
			GenreRepository::class, [], [], '', false
		);

		$this->subject->injectGenreRepository($mockRepository);

		$this->assertAttributeSame(
			$mockRepository,
			'genreRepository',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function audienceRepositoryCanBeInjected() {
		$mockRepository = $this->getMock(
			AudienceRepository::class, [], [], '', false
		);

		$this->subject->injectAudienceRepository($mockRepository);

		$this->assertAttributeSame(
			$mockRepository,
			'audienceRepository',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function venueRepositoryCanBeInjected() {
		$mockRepository = $this->getMock(
			VenueRepository::class, [], [], '', false
		);

		$this->subject->injectVenueRepository($mockRepository);

		$this->assertAttributeSame(
			$mockRepository,
			'venueRepository',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function localDriverCanBeInjected() {
		$mockLocalDriver = $this->getMock(
			LocalDriver::class
		);

		$this->subject->injectLocalDriver($mockLocalDriver);

		$this->assertAttributeSame(
			$mockLocalDriver,
			'localDriver',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getDownloadFileNameReturnsSanitizedFileName() {
		$fileName = 'foo';
		$sanitizedFileName = 'bar';

		$mockLocalDriver = $this->getMock(
			LocalDriver::class, ['sanitizeFileName']
		);
		$this->subject->injectLocalDriver($mockLocalDriver);

		$mockLocalDriver->expects($this->once())
			->method('sanitizeFileName')
			->with($fileName)
			->will($this->returnValue($sanitizedFileName));

		$this->assertSame(
			$sanitizedFileName,
			$this->subject->getDownloadFileName($fileName, false)
		);
	}

	/**
	 * @test
	 */
	public function getDownloadFileNamePrependsDate() {
		$date = date('Y-m-d_H-m');
		$fileName = 'foo';
		$expectedFileName = $date . '_' . $fileName;

		$mockLocalDriver = $this->getMock(
			LocalDriver::class, ['sanitizeFileName']
		);
		$this->subject->injectLocalDriver($mockLocalDriver);

		$mockLocalDriver->expects($this->once())
			->method('sanitizeFileName')
			->with($expectedFileName);

		$this->subject->getDownloadFileName($fileName);
	}

	/**
	 * @return array
	 */
	public function allowedFileTypesForDownloadHeadersDataProvider() {
		return [
			'csv' => ['csv', 'text/csv'],
			'txt' => ['txt', 'text/plain'],
			'pdf' => ['pdf', 'application/pdf'],
			'exe' => ['exe', 'application/octet-stream'],
			'zip' => ['zip', 'application/zip'],
			'doc' => ['doc', 'application/msword'],
			'xls' => ['xls', 'application/vnd.ms-excel'],
			'ppt' => ['ppt', 'application/vnd.ms-powerpoint'],
			'gif' => ['gif', 'image/gif'],
			'png' => ['png', 'image/png'],
			'jpeg' => ['jpeg', 'image/jpg'],
			'jpg' => ['jpg', 'image/jpg'],
			'mp3' => ['mp3', 'audio/mpeg'],
			'wav' => ['wav', 'audio/x-wav'],
			'mpeg' => ['mpeg', 'video/mpeg'],
			'mpg' => ['mpg', 'video/mpeg'],
			'mpe' => ['mpg', 'video/mpeg'],
			'mov' => ['mov', 'video/quicktime'],
			'avi' => ['avi', 'video/x-msvideo']
		];
	}

	/**
	 * @test
	 * @dataProvider allowedFileTypesForDownloadHeadersDataProvider
	 */
	public function sendDownloadHeadersSendsHeaderForAllowedFileTypes($fileExtension, $contentType) {
		$fileName = 'foo';
		$headers = [
			'Pragma' => 'public',
			'Expires' => 0,
			'Cache-Control' => 'public',
			'Content-Description' => 'File Transfer',
			'Content-Type' => $contentType,
			'Content-Disposition' => 'attachment; filename="' . $fileName . '.' . $fileExtension . '"',
			'Content-Transfer-Encoding' => 'binary',
		];

		$mockResponse = $this->getMock(
			Response::class, ['sendHeaders', 'setHeader']
		);
		$this->inject($this->subject, 'response', $mockResponse);
		$mockResponse->expects($this->once())
			->method('sendHeaders');

		$this->subject->_callRef('sendDownloadHeaders', $fileExtension, $fileName);

	}

	/**
	 * return array
	 */
	public function forbiddenFileTypesForDownloadHeadersDataProvider() {
		return [
			'inc' => ['inc'],
			'conf' => ['conf'],
			'sql' => ['sql'],
			'cgi' => ['cgi'],
			'htaccess' => ['htaccess'],
			'php' => ['php'],
			'php3' => ['php3'],
			'php4' => ['php4'],
			'php5' => ['php5'],
		];
	}

	/**
	 * @test
	 * @dataProvider forbiddenFileTypesForDownloadHeadersDataProvider
	 * @expectedException \Webfox\T3events\InvalidFileTypeException
	 * @expectedExceptionCode 1456009720
	 */
	public function sendDownloadHeadersDoesNotSendHeadersForForbiddenFileTypes($fileExtension) {
		$fileName = 'foo';

		$mockResponse = $this->getMock(
			Response::class, ['sendHeaders', 'setHeader']
		);
		$this->inject($this->subject, 'response', $mockResponse);
		$mockResponse->expects($this->never())
			->method('sendHeaders');

		$this->subject->_callRef('sendDownloadHeaders', $fileExtension, $fileName);

	}

	/**
	 * @test
	 */
	public function sendDownloadHeadersSendsHeadersForDefaultType() {
		$unknownValidExtension = 'foo';
		$fileName = 'bar';
		$mockResponse = $this->getMock(
			Response::class, ['sendHeaders', 'setHeader']
		);
		$this->inject($this->subject, 'response', $mockResponse);
		$mockResponse->expects($this->once())
			->method('sendHeaders');

		$this->subject->_callRef('sendDownloadHeaders', $unknownValidExtension, $fileName);
	}

	/**
	 * @test
	 */
	public function sendDownloadHeadersSetsResponse() {
		$unknownValidExtension = 'foo';
		$fileName = 'bar';
		$mockResponse = $this->getMock(
			Response::class, ['sendHeaders', 'setHeader']
		);
		$mockObjectManager = $this->mockObjectManager();

		$mockObjectManager->expects($this->once())
			->method('get')
			->with(Response::class)
			->will($this->returnValue($mockResponse));

		$this->subject->_callRef('sendDownloadHeaders', $unknownValidExtension, $fileName);
	}
}
