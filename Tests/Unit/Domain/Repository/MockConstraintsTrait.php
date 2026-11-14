<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;

trait MockConstraintsTrait
{
    /**
     * @return ConstraintInterface|MockObject
     */
    protected function getMockConstraint()
    {
        return $this->getMockBuilder(ConstraintInterface::class)->getMockForAbstractClass();
    }
}
