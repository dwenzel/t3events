<?php
namespace Webfox\T3events\Tests\Resource;

use TYPO3\CMS\Core\Tests\UnitTestCase;
use Webfox\T3events\Resource\VectorImage;

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
class VectorImageTest extends UnitTestCase
{
    /**
     * @var VectorImage
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            VectorImage::class, ['dummy'], [], '', true
        );
    }

    /**
     * @test
     */
    public function hideElementsDoesNotSetAttributeForMissingElements()
    {
        $unchangedSubject = clone $this->subject;
        $nonExistingElementIds = ['foo'];
        $this->subject->hideElements($nonExistingElementIds);

        $this->assertEquals(
            $this->subject,
            $unchangedSubject
        );
    }

    /**
     * @test
     */
    public function hideElementsSetsAttributeForExistingElement()
    {
        $this->subject = $this->getAccessibleMock(
            VectorImage::class, ['getElementById'], [], '', true
        );

        /** @var \DOMElement $mockElement */
        $mockElement = $this->getMock(
            \DOMElement::class, ['setAttribute'], ['fooTagName']
        );
        $validId = 'foo';
        $existingElementIds = [$validId];

        $this->subject->expects($this->once())
            ->method('getElementById')
            ->with($validId)
            ->will($this->returnValue($mockElement));

        $mockElement->expects($this->once())
            ->method('setAttribute')
            ->with('style', 'display:none');

        $this->subject->hideElements($existingElementIds);
    }

    /**
     * @test
     */
    public function showElementsDoesNotSetAttributeForMissingElements()
    {
        $unchangedSubject = clone $this->subject;
        $nonExistingElementIds = ['foo'];
        $this->subject->showElements($nonExistingElementIds);

        $this->assertEquals(
            $this->subject,
            $unchangedSubject
        );
    }

    /**
     * @test
     */
    public function showElementsSetsAttributeForExistingElement()
    {
        $this->subject = $this->getAccessibleMock(
            VectorImage::class, ['getElementById'], [], '', true
        );

        /** @var \DOMElement $mockElement */
        $mockElement = $this->getMock(
            \DOMElement::class, ['setAttribute'], ['fooTagName']
        );
        $validId = 'foo';
        $existingElementIds = [$validId];

        $this->subject->expects($this->once())
            ->method('getElementById')
            ->with($validId)
            ->will($this->returnValue($mockElement));

        $mockElement->expects($this->once())
            ->method('setAttribute')
            ->with('style', 'display:inline');

        $this->subject->showElements($existingElementIds);
    }

    /**
     * @test
     */
    public function replaceNodeTextDoesNotChangeDocumentIfElementDoesNotExist()
    {
        $nonExistingElementId = 'foo';
        $this->subject = $this->getAccessibleMock(
            VectorImage::class, ['removeChild', 'appendChild'], [], '', true
        );
        $this->subject->expects($this->never())
            ->method('removeChild');
        $this->subject->expects($this->never())
            ->method('appendChild');

        $this->subject->replaceNodeText($nonExistingElementId, 'bar');
    }
}
