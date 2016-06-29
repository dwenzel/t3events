<?php
namespace Webfox\T3events\Tests\Controller;

use Webfox\T3events\Domain\Repository\AudienceRepository;
use Webfox\T3events\Domain\Repository\CompanyRepository;
use Webfox\T3events\Domain\Repository\EventTypeRepository;
use Webfox\T3events\Domain\Repository\GenreRepository;
use Webfox\T3events\Domain\Repository\VenueRepository;
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
}
