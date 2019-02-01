<?php

namespace DWenzel\T3events\Tests\Controller\Backend;

use DWenzel\T3events\Controller\Backend\FormTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
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
    }

    /**
     * provides data for test getModuleKeyReturnsGetParameter
     * @return array
     */
    public function getModuleKeyReturnsGetParameterDataProvider()
    {
        $key = 'M';
        $value = 'Events_T3eventsM1';
        if (VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000)
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
        $this->assertSame(
            $expectedValue,
            $this->subject->getModuleKey()
        );
    }
}
