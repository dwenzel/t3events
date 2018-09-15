<?php

namespace DWenzel\T3events\Tests\Update;

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

use DWenzel\T3events\Update\MigrateTaskRecords;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

/**
 * Class MigrateTaskRecordsTest
 */
class MigrateTaskRecordsTest extends UnitTestCase
{
    /**
     * @var MigrateTaskRecords|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $subject;

    /**
     * @var DatabaseConnection|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $database;

    /**
     * set up the subject
     */
    public function setUp()
    {
        $this->subject = $this->getMockMigrateTaskRecords(['dummy', 'getDatabaseConnection']);
        $this->database = $this->getMockBuilder(DatabaseConnection::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'exec_SELECTquery',
                'sql_fetch_assoc',
                'sql_error',
                'debug_lastBuiltQuery',
                'admin_get_fields',
                'exec_UPDATEquery'
            ])
            ->getMock();
        $this->subject->expects($this->any())
            ->method('getDatabaseConnection')
            ->will($this->returnValue($this->database));
    }

    /**
     * @test
     */
    public function checkForUpdateInitiallyReturnsFalse()
    {
        $description = '';
        $this->assertFalse(
            $this->subject->checkForUpdate($description)
        );
    }

    /**
     * @test
     */
    public function performUpdateInitiallyReturnsFalse()
    {
        $dbQueries = [];
        $customMessages = '';
        $this->assertFalse(
            $this->subject->performUpdate($dbQueries, $customMessages)
        );
    }

    /**
     * @test
     */
    public function performUpdateAddsMessageIfTaskWithDeprecatedFieldsExist()
    {
        $dbQueries = [];
        $customMessages = [];
        $tasks = ['foo'];
        $expectedMessages = [
            [
                FlashMessage::INFO,
                MigrateTaskRecords::TITLE_UPDATE_REQUIRED,
                sprintf(MigrateTaskRecords::MESSAGE_UPDATE_REQUIRED, \count($tasks))
            ]
        ];
        $this->subject = $this->getMockMigrateTaskRecords(['getTasksWithDeprecatedProperties']);
        $this->subject->expects($this->once())
            ->method('getTasksWithDeprecatedProperties')
            ->will($this->returnValue($tasks));

        $this->subject->performUpdate($dbQueries, $customMessages);

        $this->assertSame(
            $customMessages,
            $expectedMessages
        );
    }

    /**
     * @test
     */
    public function performUpdateUpdatesValidRecords()
    {
        $validTask = [
            'uid' => 5,
            'period' => '5'
        ];
        $dbQueries = [];
        $customMessages = [];
        $tasks = [$validTask];
        $expectedFieldArray = [
            'period_duration' => $validTask['period'],
            'period' => ''
        ];
        $expectedMessages = [
            [
                FlashMessage::INFO,
                MigrateTaskRecords::TITLE_UPDATE_REQUIRED,
                sprintf(MigrateTaskRecords::MESSAGE_UPDATE_REQUIRED, \count($tasks))
            ],
            [
                FlashMessage::INFO,
                MigrateTaskRecords::TITLE_UPDATED,
                sprintf(MigrateTaskRecords::MESSAGE_UPDATED, \count($tasks))
            ]
        ];
        $this->subject = $this->getMockMigrateTaskRecords(['getTasksWithDeprecatedProperties', 'getDatabaseConnection']);
        $this->subject->expects($this->once())
            ->method('getTasksWithDeprecatedProperties')
            ->will($this->returnValue($tasks));
        $this->subject->expects($this->once())
            ->method('getDatabaseConnection')
            ->will($this->returnValue($this->database));

        $this->database->expects($this->once())
            ->method('exec_UPDATEquery')
            ->with(
                MigrateTaskRecords::TASK_TABLE,
                'uid=' . $validTask['uid'],
                $expectedFieldArray
            );
        $this->subject->performUpdate($dbQueries, $customMessages);

        $this->assertSame(
            $customMessages,
            $expectedMessages
        );
    }


    /**
     * @param array $methods
     * @return MigrateTaskRecords|MockObject
     */
    protected function getMockMigrateTaskRecords(array $methods = [])
    {
        return $this->getMockBuilder(MigrateTaskRecords::class)
            ->setMethods($methods)
            ->getMock();
    }
}
