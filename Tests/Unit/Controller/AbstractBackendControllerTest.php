<?php

namespace DWenzel\T3events\Tests\Controller;

use DWenzel\T3events\Controller\AbstractBackendController;
use DWenzel\T3events\Domain\Repository\AudienceRepository;
use DWenzel\T3events\Domain\Repository\CompanyRepository;
use DWenzel\T3events\Domain\Repository\EventTypeRepository;
use DWenzel\T3events\Domain\Repository\GenreRepository;
use DWenzel\T3events\Domain\Repository\VenueRepository;
use Nimut\TestingFramework\TestCase\UnitTestCase;

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
class AbstractBackendControllerTest extends UnitTestCase
{

    /**
     * @var AbstractBackendController
     */
    protected $subject;

    /**
     * set up
     */
    protected function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            AbstractBackendController::class, ['dummy'], [], '', false
        );
    }

    /**
     * @test
     */
    public function eventTypeRepositoryCanBeInjected()
    {
        $mockRepository = $this->getMockBuilder(EventTypeRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

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
    public function companyRepositoryCanBeInjected()
    {
        /** @var CompanyRepository|\PHPUnit_Framework_MockObject_MockObject $mockRepository */
        $mockRepository = $this->getMockBuilder(CompanyRepository::class)
            ->disableOriginalConstructor()->getMock();

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
    public function genreRepositoryCanBeInjected()
    {
        /** @var GenreRepository|\PHPUnit_Framework_MockObject_MockObject $mockRepository */
        $mockRepository = $this->getMockBuilder(GenreRepository::class)
            ->disableOriginalConstructor()->getMock();

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
    public function audienceRepositoryCanBeInjected()
    {
        /** @var AudienceRepository|\PHPUnit_Framework_MockObject_MockObject $mockRepository */
        $mockRepository = $this->getMockBuilder(AudienceRepository::class)
            ->disableOriginalConstructor()->getMock();

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
    public function venueRepositoryCanBeInjected()
    {
        /** @var VenueRepository|\PHPUnit_Framework_MockObject_MockObject $mockRepository */
        $mockRepository = $this->getMockBuilder(VenueRepository::class)
            ->disableOriginalConstructor()->getMock();

        $this->subject->injectVenueRepository($mockRepository);

        $this->assertAttributeSame(
            $mockRepository,
            'venueRepository',
            $this->subject
        );
    }
}
