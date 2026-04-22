<?php

namespace DWenzel\T3events\Utility;

use DWenzel\T3events\Resource\ResourceFactory;
use DWenzel\T3events\Tests\Unit\Object\MockObjectManagerTrait;
use Nimut\TestingFramework\MockObject\AccessibleMockObjectInterface;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

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
class DummyController
{
}

/**
 * Class SettingsUtilityTest
 *
 * @package DWenzel\T3events\Utility
 */
class SettingsUtilityTest extends UnitTestCase
{
    use MockObjectManagerTrait;

    const SKIP_MESSAGE_FILEREFERENCE = 'Skipped due to incompatible implementation in core.';

    /**
     * @var SettingsUtility|AccessibleMockObjectInterface|MockObject
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            SettingsUtility::class, ['dummy']
        );
        $this->objectManager = $this->getMockObjectManager();
        $this->subject->injectObjectManager($this->objectManager);
    }

    /**
     * @test
     */
    public function getValueByKeyInitiallyReturnsNull()
    {
        $config = [];
        self::assertNull(
            $this->subject->getValueByKey(null, $config, 'foo')
        );
    }

    /**
     * @test
     */
    public function getValueByKeyReturnsStringValueIfFieldIsNotSet()
    {
        $key = 'foo';
        $config = [
            $key => 'bar'
        ];
        $expectedValue = $config[$key];

        self::assertSame(
            $expectedValue,
            $this->subject->getValueByKey(null, $config, $key)
        );
    }

    /**
     * @test
     */
    public function getValueByKeyReturnsValueFromObjectByPath()
    {
        $mockParentObject = $this->getAccessibleMock(
            AbstractDomainObject::class, ['getFoo']
        );
        $mockChildObject = $this->getAccessibleMock(
            AbstractDomainObject::class, ['getBar']
        );
        $expectedValue = 'baz';
        $mockChildObject->_set('bar', $expectedValue);
        $mockParentObject->_set('foo', $mockChildObject);

        $key = 'fooValue';
        $config = [
            $key => [
                'field' => 'foo.bar'
            ]
        ];
        $mockParentObject->expects(self::atLeastOnce())
            ->method('getFoo')
            ->willReturn($mockChildObject);
        $mockChildObject->expects(self::atLeastOnce())
            ->method('getBar')
            ->willReturn($expectedValue);

        self::assertSame(
            $expectedValue,
            $this->subject->getValueByKey($mockParentObject, $config, $key)
        );
    }

    /**
     * @test
     */
    public function getValueByKeyReturnsDefaultValueIfObjectByPathReturnsNull()
    {
        $mockParentObject = $this->getAccessibleMock(
            AbstractDomainObject::class, ['getFoo']
        );
        $expectedFallbackValue = 'fallback';

        $key = 'fooValue';
        $config = [
            $key => [
                'field' => 'foo.bar',
                'default' => $expectedFallbackValue
            ]
        ];
        $mockParentObject->expects(self::atLeastOnce())
            ->method('getFoo');

        self::assertSame(
            $expectedFallbackValue,
            $this->subject->getValueByKey($mockParentObject, $config, $key)
        );
    }

    /**
     * @test
     */
    public function getValueByKeyWrapsFieldValue()
    {
        $cObj = new ContentObjectRenderer();
        $this->subject->injectContentObjectRenderer($cObj);

        $mockParentObject = $this->getAccessibleMock(
            AbstractDomainObject::class, ['getFoo']
        );
        $fieldValue = 'field value';
        $wrap = '|Wrap |';
        $expectedWrappedValue = 'Wrap field value';

        $key = 'fooValue';
        $config = [
            $key => [
                'field' => 'foo',
                'noTrimWrap' => $wrap
            ]
        ];
        $mockParentObject->expects(self::atLeastOnce())
            ->method('getFoo')
            ->willReturn($fieldValue);

        self::assertSame(
            $expectedWrappedValue,
            $this->subject->getValueByKey($mockParentObject, $config, $key)
        );
    }

    /**
     * @test
     */
    public function injectContentObjectRendererSetsObject()
    {
        /** @var ContentObjectRenderer|MockObject $mockContentObjectRenderer */
        $mockContentObjectRenderer = $this->getMockBuilder(ContentObjectRenderer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->subject->injectContentObjectRenderer($mockContentObjectRenderer);
        self::assertAttributeEquals(
            $mockContentObjectRenderer,
            'contentObjectRenderer',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getControllerKeyReturnsKeyIfSet()
    {
        $key = 'dummy';
        $controllerKeys = [
            DummyController::class => $key
        ];
        $this->subject->_set('controllerKeys', $controllerKeys);

        self::assertSame(
            $key,
            $this->subject->getControllerKey(new DummyController())
        );
    }

    /**
     * @test
     */
    public function getControllerKeyReturnsKeyByClassName()
    {
        $key = 'dummy';

        self::assertSame(
            $key,
            $this->subject->getControllerKey(new DummyController())
        );
    }

    /**
     * @test
     */
    public function getFileStorageInitiallyReturnsEmptyObjectStorage()
    {
        $config = [];
        /** @var DomainObjectInterface|MockObject $mockObject */
        $mockObject = $this->getMockBuilder(DomainObjectInterface::class)->getMock();
        $mockObjectStorage = $this->getMockObjectStorage();

        $this->objectManager->expects(self::once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will(self::returnValue($mockObjectStorage));

        self::assertSame(
            $mockObjectStorage,
            $this->subject->getFileStorage(
                $mockObject, $config
            )
        );
    }

    /**
     * @test
     */
    public function getFileStorageReturnsNonEmptyFileReferenceStorageFromObject()
    {
        $this->subject = $this->getAccessibleMock(
            SettingsUtility::class, ['getValue']
        );
        $this->subject->injectObjectManager($this->objectManager);

        $config = [
            'field' => 'foo'
        ];
        /** @var AbstractDomainObject|MockObject $mockObject */
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMockObjectStorage();
        $mockObjectStorageFromObject = $this->getMockObjectStorage(['count', 'current']);
        $mockFileReference = $this->getMockFileReference();
        $this->subject->expects(self::once())
            ->method('getValue')
            ->with($mockObject, $config)
            ->will(self::returnValue($mockObjectStorageFromObject));
        $mockObjectStorageFromObject->expects($this->any())
            ->method('count')
            ->will(self::returnValue(5));
        $mockObjectStorageFromObject->expects($this->any())
            ->method('current')
            ->will(self::returnValue($mockFileReference));

        $this->objectManager->expects(self::once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will(self::returnValue($mockObjectStorage));

        self::assertSame(
            $mockObjectStorageFromObject,
            $this->subject->getFileStorage(
                $mockObject, $config
            )
        );
    }

    /**
     * @test
     */
    public function getFileStorageReturnsStorageWithFileReferenceFromObject()
    {
        $this->subject = $this->getAccessibleMock(
            SettingsUtility::class, ['getValue']
        );
        $this->subject->injectObjectManager($this->objectManager);

        $config = ['foo'];
        /** @var AbstractDomainObject|MockObject $mockObject */
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMockObjectStorage(['count', 'attach']);
        $mockFileReference = $this->getMockFileReference();
        $this->subject->expects(self::once())
            ->method('getValue')
            ->with($mockObject, $config)
            ->will(self::returnValue($mockFileReference));
        $mockObjectStorage->expects(self::once())
            ->method('attach')
            ->with($mockFileReference);
        $mockObjectStorage->expects($this->any())
            ->method('count')
            ->will(self::returnValue(1));

        $this->objectManager->expects(self::once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will(self::returnValue($mockObjectStorage));

        $this->subject->getFileStorage($mockObject, $config);
    }

    /**
     * @test
     */
    public function getFileStorageAddsDefaultValueIfStorageFromObjectIsEmpty()
    {
        $defaultValue = 'bar';
        $config = [
            'field' => 'foo',
            'default' => $defaultValue
        ];
        /** @var AbstractDomainObject|MockObject $mockObject */
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMockObjectStorage(['count', 'attach']);
        $mockFile = $this->getMockFile();
        $mockFileReference = $this->getMockFileReference();
        $mockObjectStorage->expects(self::once())
            ->method('attach')
            ->with($mockFileReference);
        $mockObjectStorage->expects($this->any())
            ->method('count')
            ->will(self::returnValue(0));
        $mockResourceFactory = $this->mockResourceFactory();

        $mockResourceFactory->expects(self::once())
            ->method('getFileObjectByCombinedIdentifier')
            ->with($defaultValue)
            ->will(self::returnValue($mockFile));
        $mockResourceFactory->expects(self::once())
            ->method('createFileReferenceFromFileObject')
            ->with($mockFile)
            ->will(self::returnValue($mockFileReference));
        $this->objectManager->expects(self::once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will(self::returnValue($mockObjectStorage));

        $this->subject->getFileStorage($mockObject, $config);
    }

    /**
     * @test
     */
    public function getFileStorageAddsAlwaysValue()
    {
        $defaultValue = 'bar';
        $alwaysValue = 'baz';
        $config = [
            'field' => 'foo',
            'default' => $defaultValue,
            'always' => $alwaysValue
        ];
        /** @var AbstractDomainObject|MockObject $mockObject */
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMockObjectStorage(['count', 'attach']);
        $mockFile = $this->getMockFile();
        $mockFileReference = $this->getMockFileReference();
        $mockObjectStorage->expects($this->exactly(2))
            ->method('attach')
            ->with($mockFileReference);
        $mockObjectStorage->expects($this->any())
            ->method('count')
            ->will(self::returnValue(0));
        $mockResourceFactory = $this->mockResourceFactory();

        $mockResourceFactory->expects($this->exactly(2))
            ->method('getFileObjectByCombinedIdentifier')
            ->withConsecutive(
                [$defaultValue], [$alwaysValue]
            )
            ->will(self::returnValue($mockFile));
        $mockResourceFactory->expects($this->exactly(2))
            ->method('createFileReferenceFromFileObject')
            ->with($mockFile)
            ->will(self::returnValue($mockFileReference));
        $this->objectManager->expects(self::once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will(self::returnValue($mockObjectStorage));

        $this->subject->getFileStorage($mockObject, $config);
    }

    /**
     * @return mixed
     */
    protected function mockResourceFactory()
    {
        /** @var ResourceFactory|MockObject $mockResourceFactory */
        $mockResourceFactory = $this->getMockBuilder(ResourceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(
                ['getFileObjectByCombinedIdentifier', 'createFileReferenceFromFileObject']
            )->getMock();
        $this->subject->injectResourceFactory($mockResourceFactory);

        return $mockResourceFactory;
    }

    /**
     * @param array $methods Methods to mock
     * @return ObjectStorage|MockObject
     */
    protected function getMockObjectStorage(array $methods = [])
    {
        return $this->getMockBuilder(ObjectStorage::class)
            ->setMethods($methods)->getMock();
    }

    /**
     * @return MockObject
     */
    protected function getMockFileReference(): MockObject
    {
        return $this->getMockBuilder(FileReference::class)->getMock();
    }

    /**
     * @return mixed
     */
    protected function getMockFile()
    {
        return $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();
    }
}
