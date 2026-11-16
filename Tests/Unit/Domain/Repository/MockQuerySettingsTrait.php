<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

trait MockQuerySettingsTrait
{
    /**
     * @param array $methods Methods to mock
     * @return QuerySettingsInterface|MockObject
     */
    protected function getMockQuerySettings(array $methods = [])
    {
        $builder = $this->getMockBuilder(QuerySettingsInterface::class);
        if (!empty($methods)) {
            $builder->onlyMethods($methods);
        }
        return $builder->getMockForAbstractClass();
    }
}
