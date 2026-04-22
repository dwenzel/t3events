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
use DWenzel\T3events\Controller\PersistenceManagerTrait;
use TYPO3\CMS\Extbase\Persistence\PersistenceManagerInterface;

/**
 * Class PersistenceManagerTraitTest
 *
 * @package DWenzel\T3events\Tests\Controller
 */
class PersistenceManagerTraitTest extends UnitTestCase
{
    /**
     * @var PersistenceManagerTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            PersistenceManagerTrait::class
        );
    }

    /**
     * @test
     */
    public function persistenceManagerCanBeInjected()
    {
        $persistenceManager = $this->getMockForAbstractClass(
            PersistenceManagerInterface::class
        );

        $this->subject->injectPersistenceManager($persistenceManager);

        $this->assertAttributeSame(
            $persistenceManager,
            'persistenceManager',
            $this->subject
        );
    }
}
