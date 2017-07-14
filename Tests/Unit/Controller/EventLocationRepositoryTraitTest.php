<?php
namespace DWenzel\T3events\Tests\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use Nimut\TestingFramework\TestCase\UnitTestCase;
use DWenzel\T3events\Controller\EventLocationRepositoryTrait;
use DWenzel\T3events\Domain\Repository\EventLocationRepository;

/**
 * Class EventLocationRepositoryTraitTest
 */
class EventLocationRepositoryTraitTest extends UnitTestCase
{
    /**
     * @var EventLocationRepositoryTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            EventLocationRepositoryTrait::class
        );
    }

    /**
     * @test
     */
    public function eventLocationRepositoryCanBeInjected()
    {
        $eventLocationRepository = $this->getMock(
            EventLocationRepository::class, [], [], '', false
        );

        $this->subject->injectEventLocationRepository($eventLocationRepository);

        $this->assertAttributeSame(
            $eventLocationRepository,
            'eventLocationRepository',
            $this->subject
        );
    }
}
