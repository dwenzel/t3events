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
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use Nimut\TestingFramework\TestCase\UnitTestCase;

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
        $this->subject = $this->getMock(
            MigrateTaskRecords::class, ['dummy', 'getDatabaseConnection']
        );
        $this->database = $this->getMock(
            DatabaseConnection::class,
            [
                'exec_SELECTquery',
                'sql_fetch_assoc',
                'sql_error',
                'debug_lastBuiltQuery',
                'admin_get_fields',
                'exec_UPDATEquery'
            ], [], '', false
        );
        $this->subject->expects($this->any())
            ->method(('getDatabaseConnection'))
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
    public function checkForUpdateGetsTasksWithDeprecatedFieldsFromDatabase()
    {
        $description = '';
        $expectedFields = 'uid, period';
        $expectedWhere = 'period!=0';
        $this->database->expects($this->once())
            ->method('exec_SELECTquery')
            ->with($expectedFields, MigrateTaskRecords::TASK_TABLE, $expectedWhere);
        $this->subject->checkForUpdate($description);
    }

    /**
     * @test
     */
    public function checkForUpdateReturnsTrueIfTasksWithDeprecatedFieldsExist()
    {
        $description = '';
        $tasks = ['foo'];
        $this->subject = $this->getMock(
            MigrateTaskRecords::class, ['getTasksWithDeprecatedProperties']
        );
        $this->subject->expects($this->once())
            ->method('getTasksWithDeprecatedProperties')
            ->will($this->returnValue($tasks));

        $this->assertTrue(
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
                sprintf(MigrateTaskRecords::MESSAGE_UPDATE_REQUIRED, count($tasks))
            ]
        ];
        $this->subject = $this->getMock(
            MigrateTaskRecords::class, ['getTasksWithDeprecatedProperties']
        );
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
                sprintf(MigrateTaskRecords::MESSAGE_UPDATE_REQUIRED, count($tasks))
            ],
            [
                FlashMessage::INFO,
                MigrateTaskRecords::TITLE_UPDATED,
                sprintf(MigrateTaskRecords::MESSAGE_UPDATED, count($tasks))
            ]
        ];
        $this->subject = $this->getMock(
            MigrateTaskRecords::class, ['getTasksWithDeprecatedProperties', 'getDatabaseConnection']
        );
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
}
