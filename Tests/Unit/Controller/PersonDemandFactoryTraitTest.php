<?php

namespace DWenzel\T3events\Tests\Unit\Controller;

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

use DWenzel\T3events\Controller\PersonDemandFactoryTrait;
use DWenzel\T3events\Domain\Factory\Dto\PersonDemandFactory;
use Nimut\TestingFramework\TestCase\UnitTestCase;

class PersonDemandFactoryTraitTest extends UnitTestCase
{
    /**
     * @var PersonDemandFactoryTrait
     */
    protected $subject;

    /**
     * set up the subject
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockForTrait(
            PersonDemandFactoryTrait::class
        );
    }

    /**
     * @test
     */
    public function personDemandFactoryCanBeInjected()
    {
        /** @var PersonDemandFactory|\PHPUnit_Framework_MockObject_MockObject $personDemandFactory */
        $personDemandFactory = $this->getMockBuilder(PersonDemandFactory::class)
            ->getMock();

        $this->subject->injectPersonDemandFactory($personDemandFactory);

        $this->assertAttributeSame(
            $personDemandFactory,
            'personDemandFactory',
            $this->subject
        );
    }
}
