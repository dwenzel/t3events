<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Model\Dto;

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use PHPUnit\Framework\MockObject\MockObject;

trait MockDemandTrait
{
    /**
     * @param array $methods Methods to mock
     * @return DemandInterface|MockObject
     */
    protected function getMockDemand(array $methods = [])
    {
        $builder = $this->getMockBuilder(DemandInterface::class);
        if (!empty($methods)) {
            $builder->onlyMethods($methods);
        }
        return $builder->getMockForAbstractClass();
    }
}
