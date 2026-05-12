<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings;

trait MockQuerySettingsTrait
{
    /**
     * @param array $methods Methods to mock
     * @return Typo3QuerySettings|MockObject
     */
    protected function getMockQuerySettings(array $methods = [])
    {
        // Must mock Typo3QuerySettings (not the interface) so GeneralUtility::addInstance()
        // type-check passes when tests register the mock via addInstance(Typo3QuerySettings::class, ...).
        $builder = $this->getMockBuilder(Typo3QuerySettings::class)
            ->disableOriginalConstructor();
        if (!empty($methods)) {
            $builder->onlyMethods($methods);
        }
        return $builder->getMock();
    }
}
