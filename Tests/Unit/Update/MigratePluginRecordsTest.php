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

use DWenzel\T3events\Update\MigratePluginRecords;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use TYPO3\CMS\Core\Tests\UnitTestCase;

/**
 * Class MigratePluginRecordsTest
 */
class MigratePluginRecordsTest extends UnitTestCase
{
    /**
     * @var MigratePluginRecords|\PHPUnit_Framework_MockObject_MockObject
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
            MigratePluginRecords::class, ['dummy', 'getDatabaseConnection']
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
    public function checkForUpdateGetsPluginsWithDeprecatedFieldsFromDatabase()
    {
        $description = '';
        $expectedFields = 'uid, period';
        $expectedWhere = 'period!=0';
        $this->database->expects($this->once())
            ->method('exec_SELECTquery')
            ->with($expectedFields, MigratePluginRecords::Plugin_TABLE, $expectedWhere);
        $this->subject->checkForUpdate($description);
    }

    /**
     * @test
     */
    public function checkForUpdateReturnsTrueIfPluginsWithDeprecatedFieldsExist()
    {
        $this->markTestSkipped();
        $description = '';
        $Plugins = ['foo'];
        $this->subject = $this->getMock(
            MigratePluginRecords::class, ['getPluginsWithDeprecatedProperties']
        );
        $this->subject->expects($this->once())
            ->method('getPluginsWithDeprecatedProperties')
            ->will($this->returnValue($Plugins));

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
    public function performUpdateAddsMessageIfPluginWithDeprecatedFieldsExist()
    {
        $this->markTestSkipped();

        $dbQueries = [];
        $customMessages = [];
        $Plugins = ['foo'];
        $expectedMessages = [
            [
                FlashMessage::INFO,
                MigratePluginRecords::TITLE_UPDATE_REQUIRED,
                sprintf(MigratePluginRecords::MESSAGE_UPDATE_REQUIRED, count($Plugins))
            ]
        ];
        $this->subject = $this->getMock(
            MigratePluginRecords::class, ['getPluginsWithDeprecatedProperties']
        );
        $this->subject->expects($this->once())
            ->method('getPluginsWithDeprecatedProperties')
            ->will($this->returnValue($Plugins));

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
        $this->markTestSkipped();

        $validPlugin = [
            'uid' => 5,
            'period' => '5'
        ];
        $dbQueries = [];
        $customMessages = [];
        $Plugins = [$validPlugin];
        $expectedFieldArray = [
            'period_duration' => $validPlugin['period'],
            'period' => ''
        ];
        $expectedMessages = [
            [
                FlashMessage::INFO,
                MigratePluginRecords::TITLE_UPDATE_REQUIRED,
                sprintf(MigratePluginRecords::MESSAGE_UPDATE_REQUIRED, count($Plugins))
            ],
            [
                FlashMessage::INFO,
                MigratePluginRecords::TITLE_UPDATED,
                sprintf(MigratePluginRecords::MESSAGE_UPDATED, count($Plugins))
            ]
        ];
        $this->subject = $this->getMock(
            MigratePluginRecords::class, ['getPluginsWithDeprecatedProperties', 'getDatabaseConnection']
        );
        $this->subject->expects($this->once())
            ->method('getPluginsWithDeprecatedProperties')
            ->will($this->returnValue($Plugins));
        $this->subject->expects($this->once())
            ->method('getDatabaseConnection')
            ->will($this->returnValue($this->database));

        $this->database->expects($this->once())
            ->method('exec_UPDATEquery')
            ->with(
                MigratePluginRecords::Plugin_TABLE,
                'uid=' . $validPlugin['uid'],
                $expectedFieldArray
            );
        $this->subject->performUpdate($dbQueries, $customMessages);

        $this->assertSame(
            $customMessages,
            $expectedMessages
        );
    }
}
