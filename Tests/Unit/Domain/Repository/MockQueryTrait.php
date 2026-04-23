<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;

use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

trait MockQueryTrait
{
    /**
     * @return QueryInterface|MockObject
     */
    protected function getMockQuery(array $methods = [])
    {
        $builder = $this->getMockBuilder(QueryInterface::class)
            ->disableOriginalConstructor();
        if (!empty($methods)) {
            $builder->onlyMethods($methods);
        }
        return $builder->getMockForAbstractClass();
    }
}
