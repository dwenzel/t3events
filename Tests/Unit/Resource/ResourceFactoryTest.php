<?php

namespace DWenzel\T3events\Tests\Unit\Resource;

use DWenzel\T3events\Resource\ResourceFactory;
use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Folder;
use TYPO3\CMS\Extbase\Domain\Model\FileReference as ExtbaseFileReference;

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
class ResourceFactoryTest extends UnitTestCase
{
    use MockObjectManagerTrait;

    /**
     * @var ResourceFactory|MockObject
     */
    protected $subject;

    /**
     * set up
     */
    protected function setUp(): void
    {
        $this->subject = $this->getMockBuilder(ResourceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['retrieveFileOrFolderObject'])
            ->getMock();
        $this->objectManager = $this->getMockObjectManager();
        $this->subject->injectObjectManager($this->objectManager);
    }

    /**
     * @test
     */
    public function getFileObjectByCombinedIdentifierInitiallyReturnsNull()
    {
        self::assertNull(
            $this->subject->getFileObjectByCombinedIdentifier('foo')
        );
    }

    /**
     * @test
     */
    public function getFileObjectByCombinedIdentifierReturnsNullForFolder()
    {
        $mockFolder = $this->getMockBuilder(Folder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->expects(self::once())
            ->method('retrieveFileOrFolderObject')
            ->willReturn($mockFolder);

        self::assertNull(
            $this->subject->getFileObjectByCombinedIdentifier('foo')
        );
    }

    /**
     * @test
     */
    public function getFileObjectByCombinedIdentifierReturnsFile()
    {
        $mockFile = $this->getMockBuilder(FileInterface::class)
            ->getMock();
        $this->subject->expects(self::once())
            ->method('retrieveFileOrFolderObject')
            ->willReturn($mockFile);
        self::assertSame(
            $mockFile,
            $this->subject->getFileObjectByCombinedIdentifier('foo')
        );
    }

    /**
     * @test
     */
    public function createFileReferenceFromFileObjectCreatesObject()
    {
        $this->subject = $this->getMockBuilder(ResourceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['createFileReferenceObject'])
            ->getMock();
        $this->subject->injectObjectManager($this->objectManager);
        /** @var FileReference|MockObject $mockCoreFileReference */
        $mockCoreFileReference = $this->getMockBuilder(FileReference::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockExtbaseFileReference = $this->getMockBuilder(
            ExtbaseFileReference::class)
            ->setMethods(['setOriginalResource'])
            ->getMock();
        /** @var File|MockObject $mockFileObject */
        $mockFileObject = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->expects(self::once())
            ->method('createFileReferenceObject')
            ->willReturn($mockCoreFileReference);
        /** @noinspection PhpParamsInspection */
        $this->objectManager->expects(self::once())
            ->method('get')
            ->with(ExtbaseFileReference::class)
            ->willReturn($mockExtbaseFileReference);

        self::assertSame(
            $mockExtbaseFileReference,
            $this->subject->createFileReferenceFromFileObject($mockFileObject)
        );
    }
}
