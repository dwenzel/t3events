<?php

namespace DWenzel\T3events\Tests\Resource;

use DWenzel\T3events\Resource\CoreResourceFactoryInterface;
use DWenzel\T3events\Resource\ResourceFactory;
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
    /**
     * @var ResourceFactory
     */
    protected $subject;

    /**
     * @var CoreResourceFactoryInterface|MockObject
     */
    protected $coreResourceFactory;

    /**
     * set up
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->coreResourceFactory = $this->getMockBuilder(CoreResourceFactoryInterface::class)
            ->getMock();
        $this->subject = new ResourceFactory($this->coreResourceFactory);
    }

    /**
     * @test
     */
    public function getFileObjectByCombinedIdentifierInitiallyReturnsNull()
    {
        $this->coreResourceFactory->method('retrieveFileOrFolderObject')
            ->willReturn(null);

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
        $this->coreResourceFactory->expects(self::once())
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
        $this->coreResourceFactory->expects(self::once())
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
        /** @var FileReference|MockObject $mockCoreFileReference */
        $mockCoreFileReference = $this->getMockBuilder(FileReference::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getUid', 'getOriginalFile'])
            ->getMock();

        /** @var File|MockObject $mockFileObject */
        $mockFileObject = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockFileObject->method('getUid')->willReturn(1);

        $mockCoreFileReference->method('getUid')->willReturn(1);
        $mockCoreFileReference->method('getOriginalFile')->willReturn($mockFileObject);

        $this->coreResourceFactory->expects(self::once())
            ->method('createFileReferenceObject')
            ->willReturn($mockCoreFileReference);

        $result = $this->subject->createFileReferenceFromFileObject($mockFileObject);

        self::assertInstanceOf(ExtbaseFileReference::class, $result);
    }
}
