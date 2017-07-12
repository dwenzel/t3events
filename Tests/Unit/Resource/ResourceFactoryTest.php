<?php
namespace DWenzel\T3events\Tests\Resource;

use DWenzel\T3events\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\Folder;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Object\ObjectManager;

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
     * @var \DWenzel\T3events\Resource\ResourceFactory
     */
    protected $subject;

    /**
     * set up
     */
    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            \DWenzel\T3events\Resource\ResourceFactory::class, ['dummy']
        );
    }

    /**
     * @test
     */
    public function objectManagerCanBeInjected()
    {
        $mockObjectManager = $this->getMock(
            ObjectManager::class
        );
        $this->subject->injectObjectManager($mockObjectManager);

        $this->assertAttributeSame(
            $mockObjectManager,
            'objectManager',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getFileObjectByCombinedIdentifierInitiallyReturnsNull()
    {
        $this->subject = $this->getAccessibleMock(
            ResourceFactory::class, ['retrieveFileOrFolderObject']
        );
        $this->assertNull(
            $this->subject->getFileObjectByCombinedIdentifier('foo')
        );
    }

    /**
     * @test
     */
    public function getFileObjectByCombinedIdentifierReturnsNullForFolder()
    {
        $this->subject = $this->getAccessibleMock(
            ResourceFactory::class, ['retrieveFileOrFolderObject']
        );
        $mockFolder = $this->getMock(
            Folder::class, [], [], '', false
        );
        $this->subject->expects($this->once())
            ->method('retrieveFileOrFolderObject')
            ->will($this->returnValue($mockFolder));
        $this->assertNull(
            $this->subject->getFileObjectByCombinedIdentifier('foo')
        );
    }

    /**
     * @test
     */
    public function getFileObjectByCombinedIdentifierReturnsFile()
    {
        $this->subject = $this->getAccessibleMock(
            ResourceFactory::class, ['retrieveFileOrFolderObject']
        );
        $mockFile = $this->getMock(
            FileInterface::class, [], [], '', false
        );
        $this->subject->expects($this->once())
            ->method('retrieveFileOrFolderObject')
            ->will($this->returnValue($mockFile));
        $this->assertSame(
            $mockFile,
            $this->subject->getFileObjectByCombinedIdentifier('foo')
        );
    }

    /**
     * @test
     */
    public function createFileReferenceFromFileObjectCreatesObject()
    {
        $this->subject = $this->getAccessibleMock(
            \DWenzel\T3events\Resource\ResourceFactory::class, ['createFileReferenceObject']
        );
        $mockCoreFileReference = $this->getMock(
            FileReference::class, [], [], '', false
        );
        $mockExtbaseFileReference = $this->getMock(
            \TYPO3\CMS\Extbase\Domain\Model\FileReference::class,
            ['setOriginalResource'], [], '', false
        );
        $mockFileObject = $this->getMock(
            File::class, [], [], '', false
        );
        $this->subject->expects($this->once())
            ->method('createFileReferenceObject')
            ->will($this->returnValue($mockCoreFileReference));
        $mockObjectManager = $this->mockObjectManager();
        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(\TYPO3\CMS\Extbase\Domain\Model\FileReference::class)
            ->will($this->returnValue($mockExtbaseFileReference));

        $this->assertSame(
            $mockExtbaseFileReference,
            $this->subject->createFileReferenceFromFileObject($mockFileObject)
        );
    }

    /**
     * @return mixed
     */
    protected function mockObjectManager()
    {
        $mockObjectManager = $this->getMock(
            ObjectManager::class
        );
        $this->subject->injectObjectManager($mockObjectManager);

        return $mockObjectManager;
    }
}
