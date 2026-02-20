<?php

namespace DWenzel\T3events\Tests\Unit\Domain\Repository;
use PHPUnit\Framework\MockObject\MockBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Extbase\Persistence\Generic\QuerySettingsInterface;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2018 Dirk Wenzel <wenzel@cps-it.de>
 *  All rights reserved
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 * A copy is found in the text file GPL.txt and important notices to the license
 * from the author is found in LICENSE.txt distributed with these scripts.
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

trait MockQuerySettingsTrait
{
    /**
     * Returns a builder object to create mock objects using a fluent interface.
     *
     * @param string|string[] $className
     *
     * @return MockBuilder
     */
    abstract public function getMockBuilder(string $className): MockBuilder;

    /**
     * @param array $methods Methods to mock
     * @return QuerySettingsInterface|MockObject
     */
    protected function getMockQuerySettings(array $methods = [])
    {
        return $this->getMockBuilder(QuerySettingsInterface::class)
            ->setMethods($methods)
            ->getMockForAbstractClass();
    }

}
