<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model;

	/***************************************************************
	 *  Copyright notice
	 *  (c) 2012 Dirk Wenzel <wenzel@dWenzel01.de>, Agentur DWenzel
	 *            Michael Kasten <kasten@dWenzel01.de>, Agentur DWenzel
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
use DWenzel\T3events\Domain\Model\Notification;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Test case for class \DWenzel\T3events\Domain\Model\Notification.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 * @package TYPO3
 * @subpackage Events
 * @author Dirk Wenzel <wenzel@dWenzel01.de>
 * @author Michael Kasten <kasten@dWenzel01.de>
 */
class NotificationTest extends UnitTestCase {

	/**
	 * @var \DWenzel\T3events\Domain\Model\Notification
	 */
	protected $subject;

	public function setUp() {
		$this->subject = $this->getAccessibleMock(
			Notification::class, ['dummy']
		);
	}

	/**
	 * @test
	 */
	public function getRecipientReturnsInitialValueForString() {
		$this->assertNull(
			$this->subject->getRecipient()
		);
	}

	/**
	 * @test
	 */
	public function setRecipientForStringSetsRecipient() {
		$this->subject->setRecipient('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->subject->getRecipient()
		);
	}

	/**
	 * @test
	 */
	public function getSenderReturnsInitialValueForString() {
		$this->assertNull(
			$this->subject->getSender()
		);
	}

	/**
	 * @test
	 */
	public function setSenderForStringSetsSender() {
		$this->subject->setSender('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->subject->getSender()
		);
	}

	/**
	 * @test
	 */
	public function getSubjectReturnsInitialValueForString() {
		$this->assertNull(
			$this->subject->getSubject()
		);
	}

	/**
	 * @test
	 */
	public function setSubjectForStringSetsSubject() {
		$this->subject->setSubject('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->subject->getSubject()
		);
	}

	/**
	 * @test
	 */
	public function getBodytextReturnsInitialValueForString() {
		$this->assertNull(
			$this->subject->getBodytext()
		);
	}

	/**
	 * @test
	 */
	public function setBodytextForStringSetsBodytext() {
		$this->subject->setBodytext('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->subject->getBodytext()
		);
	}

	/**
	 * @test
	 */
	public function getFormatReturnsInitialValueForString() {
		$this->assertNull(
			$this->subject->getFormat()
		);
	}

	/**
	 * @test
	 */
	public function setFormatForStringSetsFormat() {
		$this->subject->setFormat('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->subject->getFormat()
		);
	}

	/**
	 * @test
	 */
	public function getSentAtForDateTimeInitiallyReturnsNull() {
		$this->assertNull(
			$this->subject->getSentAt()
		);
	}

	/**
	 * @test
	 */
	public function sentAtCanBeSet() {
		$sentAt = new \DateTime();
		$this->subject->setSentAt($sentAt);

		$this->assertSame(
			$sentAt,
			$this->subject->getSentAt()
		);
	}

	/**
     * @test
     */
    public function getSenderEmailInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getSenderEmail()
        );
    }

    /**
     * @test
     */
    public function senderEmailCanBeSet()
    {
        $email = 'foo';
        $this->subject->setSenderEmail($email);
        $this->assertSame(
            $email,
            $this->subject->getSenderEmail()
        );
    }

    /**
     * @test
     */
    public function getSenderNameInitiallyReturnsNull()
    {
        $this->assertNull(
            $this->subject->getSenderName()
        );
    }

    /**
     * @test
     */
    public function getSenderEmailReturnsValueFromLegacyField()
    {
        $sender = 'bar';
        $this->subject->setSender($sender);
        $this->assertSame(
            $sender,
            $this->subject->getSenderEmail()
        );
    }

    /**
     * @test
     */
    public function senderNameCanBeSet()
    {
        $name = 'bar';
        $this->subject->setSenderName($name);
        $this->assertSame(
            $name,
            $this->subject->getSenderName()
        );
    }
}

