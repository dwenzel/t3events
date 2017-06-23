<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model;

/***************************************************************
     *  Copyright notice
     *  (c) 2012 Dirk Wenzel <wenzel@webfox01.de>, Agentur Webfox
     *            Michael Kasten <kasten@webfox01.de>, Agentur Webfox
     *  All rights reserved
     *  This script is part of the TYPO3 project. The TYPO3 project is
     *  free software; you can redistribute it and/or modify
     *  it under the terms of the GNU General Public License as published by
     *  the Free Software Foundation; either version 2 of the License, or
     *  (at your option) any later version.
     *  The GNU General Public License can be found at
     *  http://www.gnu.org/copyleft/gpl.html.
     *  This script is distributed in the hope that it will be useful,
     *  but WITHOUT ANY WARRANTY; without even the implied warranty of
     *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
     *  GNU General Public License for more details.
     *  This copyright notice MUST APPEAR in all copies of the script!
     ***************************************************************/

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Organizer.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@webfox01.de>
 * @author Michael Kasten <kasten@webfox01.de>
 */
class OrganizerTest extends \Nimut\TestingFramework\TestCase\UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\Organizer
     */
    protected $fixture;

    public function setUp()
    {
        $this->fixture = new \DWenzel\T3events\Domain\Model\Organizer();
    }

    public function tearDown()
    {
        unset($this->fixture);
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->fixture->setName('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->fixture->getName()
        );
    }

    /**
     * @test
     */
    public function getLinkReturnsInitialValueForString()
    {
    }

    /**
     * @test
     */
    public function setLinkForStringSetsLink()
    {
        $this->fixture->setLink('Conceived at T3CON10');

        $this->assertSame(
            'Conceived at T3CON10',
            $this->fixture->getLink()
        );
    }
}
