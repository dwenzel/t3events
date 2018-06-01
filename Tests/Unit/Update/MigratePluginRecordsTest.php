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
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Database\DatabaseConnection;
use TYPO3\CMS\Core\Messaging\FlashMessage;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

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
        $this->subject = $this->getMockMigratePluginRecords(['dummy', 'getDatabaseConnection']);
        $this->database = $this->getMockBuilder(DatabaseConnection::class)
            ->disableOriginalConstructor()
            ->setMethods([
                'exec_SELECTcountRows',
                'exec_SELECTgetRows',
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
     * Provides xml strings before and after update
     *
     * @return array
     */
    public function xmlWithDeprecatedSettingsDataProvider()
    {
        $legacyXml = <<<XML
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="switchableControllerActions">
                    <value index="vDEF">Event-&gt;calendar</value>
                </field>
                <field index="settings.sortBy">
                    <value index="vDEF">performances.date</value>
                </field>
                <field index="settings.sortDirection">
                    <value index="vDEF">asc</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>
XML;

        $expectedXml = <<<XML
<?xml version="1.0" encoding="utf-8" standalone="yes" ?>
<T3FlexForms>
    <data>
        <sheet index="sDEF">
            <language index="lDEF">
                <field index="switchableControllerActions">
                    <value index="vDEF">Performance-&gt;calendar</value>
                </field>
                <field index="settings.order">
                    <value index="vDEF">performances.date|asc,performances.begin|asc</value>
                </field>
            </language>
        </sheet>
    </data>
</T3FlexForms>
XML;
        $expectedFlexFormSettings = [
            'data' => [
                'sDEF' => [
                    'lDEF' => [
                        'switchableControllerActions' => [
                            'vDEF' => 'Performance->calendar'
                        ],
                        'settings.sortBy' => [
                            'vDEF' => 'performances.date'
                        ],
                        'settings.sortDirection' => [
                            'vDEF' => 'asc'
                        ]
                    ]
                ]
            ]
        ];

        return [[$legacyXml, $expectedXml, $expectedFlexFormSettings]];
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
    public function countPluginRecordsWithDeprecatedSettingsGetsNumberOfRecordsFromDatabase()
    {
        $expectedFields = '*';
        $expectedTable = MigratePluginRecords::CONTENT_TABLE;
        $expectedWhere = MigratePluginRecords::DEPRECATED_PLUGIN_WHERE_CLAUSE;
        $this->database->expects($this->once())
            ->method('exec_SELECTcountRows')
            ->with(
                $expectedFields,
                $expectedTable,
                $expectedWhere
            );

        $this->subject->countPluginRecordsWithDeprecatedSettings();
    }

    /**
     * @test
     */
    public function checkForUpdateReturnsTrueIfPluginsWithDeprecatedSettingsFound()
    {
        $versionNumber = VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
        if ($versionNumber >= 8000000) {
            $this->markTestSkipped();
        }
        $description = '';
        $plugins = 5;
        $this->subject = $this->getMockMigratePluginRecords(['countPluginRecordsWithDeprecatedSettings']);
        $this->subject->expects($this->once())
            ->method('countPluginRecordsWithDeprecatedSettings')
            ->will($this->returnValue($plugins));

        $this->assertTrue(
            $this->subject->checkForUpdate($description)
        );
    }

    /**
     * @test
     */
    public function getPluginRecordsWithDeprecatedSettingsGetsNumberOfRecordsFromDatabase()
    {
        $expectedFields = 'uid,' . MigratePluginRecords::FLEX_FORM_FIELD;
        $expectedTable = MigratePluginRecords::CONTENT_TABLE;
        $expectedWhere = MigratePluginRecords::DEPRECATED_PLUGIN_WHERE_CLAUSE;
        $this->database->expects($this->once())
            ->method('exec_SELECTgetRows')
            ->with(
                $expectedFields,
                $expectedTable,
                $expectedWhere
            );

        $this->subject->getPluginRecordsWithDeprecatedSettings();
    }

    /**
     * @test
     */
    public function performUpdateInitiallyReturnsFalse()
    {
        $versionNumber = VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version);
        if ($versionNumber >= 8000000) {
            $this->markTestSkipped();
        }
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
        $dbQueries = [];
        $customMessages = [];
        $plugins = [[MigratePluginRecords::FLEX_FORM_FIELD]];
        $expectedMessages = [
            [
                FlashMessage::INFO,
                MigratePluginRecords::TITLE_UPDATE_REQUIRED,
                sprintf(MigratePluginRecords::MESSAGE_UPDATE_REQUIRED, count($plugins))
            ]
        ];
        $this->subject = $this->getMockMigratePluginRecords(['getPluginRecordsWithDeprecatedSettings']);
        $this->subject->expects($this->once())
            ->method('getPluginRecordsWithDeprecatedSettings')
            ->will($this->returnValue($plugins));

        $this->subject->performUpdate($dbQueries, $customMessages);

        $this->assertSame(
            $customMessages,
            $expectedMessages
        );
    }

    /**
     * @test
     * @dataProvider xmlWithDeprecatedSettingsDataProvider
     * @param string $legacyXml
     * @param string $expectedXml
     * @param array $expectedFlexFormSettings
     */
    public function performUpdateUpdatesValidRecords($legacyXml, $expectedXml, $expectedFlexFormSettings)
    {
        $validPlugin = [
            'uid' => 5,
            MigratePluginRecords::FLEX_FORM_FIELD => $legacyXml
        ];
        $dbQueries = [];
        $customMessages = [];
        $pluginRecords = [$validPlugin];
        $expectedFieldArray = [
            MigratePluginRecords::FLEX_FORM_FIELD => $expectedXml
        ];

        $this->subject = $this->getMockMigratePluginRecords(
            [
                'getPluginRecordsWithDeprecatedSettings',
                'getDatabaseConnection',
                'getFlexFormSettings'
            ]
        );
        $this->subject->expects($this->once())
            ->method('getPluginRecordsWithDeprecatedSettings')
            ->will($this->returnValue($pluginRecords));
        $this->subject->expects($this->any())
            ->method('getDatabaseConnection')
            ->will($this->returnValue($this->database));
        $this->subject->expects($this->once())
            ->method('getFlexFormSettings')
            ->willReturn($expectedFlexFormSettings);

        $this->database->expects($this->once())
            ->method('exec_UPDATEquery')
            ->with(
                MigratePluginRecords::CONTENT_TABLE,
                'uid=' . $validPlugin['uid'],
                $expectedFieldArray
            );
        $this->subject->performUpdate($dbQueries, $customMessages);
    }

    /**
     * @test
     * @dataProvider xmlWithDeprecatedSettingsDataProvider
     * @param string $legacyXml
     * @param string $expectedXml
     * @param array $expectedFlexFormSettings
     */
    public function performUpdateReturnsCorrectMessages($legacyXml, $expectedXml, $expectedFlexFormSettings)
    {
        $validPlugin = [
            'uid' => 5,
            MigratePluginRecords::FLEX_FORM_FIELD => $legacyXml
        ];
        $dbQueries = [];
        $customMessages = [];
        $pluginRecords = [$validPlugin];
        $expectedFieldArray = [
            MigratePluginRecords::FLEX_FORM_FIELD => $expectedXml
        ];
        $expectedMessages = [
            [
                FlashMessage::INFO,
                MigratePluginRecords::TITLE_UPDATE_REQUIRED,
                sprintf(MigratePluginRecords::MESSAGE_UPDATE_REQUIRED, count($pluginRecords))
            ],
            [
                FlashMessage::INFO,
                MigratePluginRecords::TITLE_UPDATED,
                sprintf(MigratePluginRecords::MESSAGE_UPDATED, count($pluginRecords))
            ]
        ];
        $this->subject = $this->getMockMigratePluginRecords(
            [
                'getPluginRecordsWithDeprecatedSettings',
                'getDatabaseConnection',
                'getFlexFormSettings'
            ]
        );
        $this->subject->expects($this->once())
            ->method('getPluginRecordsWithDeprecatedSettings')
            ->will($this->returnValue($pluginRecords));
        $this->subject->expects($this->any())
            ->method('getDatabaseConnection')
            ->will($this->returnValue($this->database));
        $this->subject->expects($this->once())
            ->method('getFlexFormSettings')
            ->willReturn($expectedFlexFormSettings);

        $this->database->expects($this->once())
            ->method('exec_UPDATEquery')
            ->with(
                MigratePluginRecords::CONTENT_TABLE,
                'uid=' . $validPlugin['uid'],
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
     * @return MigratePluginRecords|MockObject
     */
    protected function getMockMigratePluginRecords(array $methods = []) {
        return $this->getMockBuilder(MigratePluginRecords::class)
            ->setMethods($methods)
            ->getMock();
    }
}
