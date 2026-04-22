<?php

namespace DWenzel\T3events\Tests\Controller\Backend;

use DWenzel\T3events\Controller\Backend\FormTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

class FormTraitTest extends UnitTestCase
{

    /**
     * @var FormTrait|MockObject
     */
    protected $subject;

    /**
     * set up
     */
    protected function setUp()
    {
        $this->subject = $this->getMockForTrait(FormTrait::class);
        $this->setBackupGlobals(true);
    }

    /**
     * provides data for test getModuleKeyReturnsGetParameter
     * @return array
     */
    public function getModuleKeyReturnsGetParameterDataProvider()
    {
        /** @var Typo3Version $version */
        $version = GeneralUtility::makeInstance(Typo3Version::class);
        $key = 'M';
        $value = 'Events_T3eventsM1';
        if (VersionNumberUtility::convertVersionNumberToInteger($version->getVersion()) >= 9000000)
        {
            $key = 'route';
            $value = '/Events/T3eventsM1/';

        }
        $_GET[$key] = $value;
        return [
            ['expected key' => $value]
        ];
    }

    /**
     * @test
     * @dataProvider getModuleKeyReturnsGetParameterDataProvider
     * @param string $expectedValue
     */
    public function getModuleKeyReturnsGetParameter(string $expectedValue) {
        if(!defined('TYPO3_version')) {
            self::markTestSkipped('required constant `TYPO3_version` is not defined');
        }
        self::assertSame(
            $expectedValue,
            $this->subject->getModuleKey()
        );
    }
}
