<?php
namespace DWenzel\T3events\Tests\Unit\Domain\Model;

use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use DWenzel\T3events\Domain\Model\CategorizableTrait;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
class CategorizableTraitTest extends UnitTestCase
{

    /**
     * @var \DWenzel\T3events\Domain\Model\CategorizableTrait
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            CategorizableTrait::class
        );
    }

    /**
     * @test
     */
    public function getCategoriesReturnsInitialNull()
    {
        $this->assertNull(
            $this->subject->getCategories()
        );
    }

    /**
     * @test
     */
    public function setCategoryForObjectStorageContainingCategorySetsCategories()
    {
        $category = new Category();
        $objectStorageHoldingExactlyOneCategory = new ObjectStorage();
        $objectStorageHoldingExactlyOneCategory->attach($category);
        $this->subject->setCategories($objectStorageHoldingExactlyOneCategory);

        $this->assertSame(
            $objectStorageHoldingExactlyOneCategory,
            $this->subject->getCategories()
        );
    }

    /**
     * @test
     */
    public function addCategoryToObjectStorageHoldingCategory()
    {
        $category = new Category();
        $newObjectStorage = new ObjectStorage();
        $this->subject->setCategories($newObjectStorage);
        $objectStorageHoldingExactlyOneCategory = new ObjectStorage();
        $objectStorageHoldingExactlyOneCategory->attach($category);
        $this->subject->addCategory($category);

        $this->assertEquals(
            $objectStorageHoldingExactlyOneCategory,
            $this->subject->getCategories()
        );
    }

    /**
     * @test
     */
    public function removeCategoryFromObjectStorageHoldingCategory()
    {
        $category = new Category();
        $newObjectStorage = new ObjectStorage();
        $this->subject->setCategories($newObjectStorage);

        $localObjectStorage = new ObjectStorage();
        $localObjectStorage->attach($category);
        $localObjectStorage->detach($category);
        $this->subject->addCategory($category);
        $this->subject->removeCategory($category);

        $this->assertEquals(
            $localObjectStorage,
            $this->subject->getCategories()
        );
    }
}
