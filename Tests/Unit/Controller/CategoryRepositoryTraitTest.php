<?php
namespace Webfox\T3events\Tests\Controller;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Controller\CategoryRepositoryTrait;
use Webfox\T3events\Domain\Repository\CategoryRepository;

/***************************************************************
 *  Copyright notice
 *  (c) 2016 Dirk Wenzel <dirk.wenzel@cps-it.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class CategoryRepositoryTraitTest
 *
 * @package Webfox\T3events\Tests\Controller
 */
class CategoryRepositoryTraitTest extends UnitTestCase
{
    /**
     * @var CategoryRepositoryTrait
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getMockForTrait(
            CategoryRepositoryTrait::class
        );
    }

    /**
     * @test
     */
    public function categoryRepositoryCanBeInjected()
    {
        $categoryRepository = $this->getMock(
            CategoryRepository::class, [], [], '', false
        );

        $this->subject->injectCategoryRepository($categoryRepository);

        $this->assertAttributeSame(
            $categoryRepository,
            'categoryRepository',
            $this->subject
        );
    }
}
