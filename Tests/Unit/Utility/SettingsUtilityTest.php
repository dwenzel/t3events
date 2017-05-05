<?php
namespace DWenzel\T3events\Utility;

use DWenzel\T3events\Resource\ResourceFactory;
use TYPO3\CMS\Core\Resource\File;
use Nimut\TestingFramework\TestCase\UnitTestCase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractDomainObject;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Object\ObjectManager;
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

    const SKIP_MESSAGE_FILEREFERENCE = 'Skipped due to incompatible implementation in core.';

    /**
     * @var \DWenzel\T3events\Utility\SettingsUtility
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = $this->getAccessibleMock(
            SettingsUtility::class, ['dummy']
        );
    }

    /**
     * @test
     */
    public function getValueByKeyInitiallyReturnsNull()
    {
        $config = [];
        $this->assertNull(
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

        $this->assertSame(
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
        $mockParentObject->expects($this->once())
            ->method('getFoo')
            ->will($this->returnValue($mockChildObject));
        $mockChildObject->expects($this->once())
            ->method('getBar')
            ->will($this->returnValue($expectedValue));

        $this->assertSame(
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
        $mockParentObject->expects($this->once())
            ->method('getFoo');

        $this->assertSame(
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
        $mockParentObject->expects($this->once())
            ->method('getFoo')
            ->will($this->returnValue($fieldValue));

        $this->assertSame(
            $expectedWrappedValue,
            $this->subject->getValueByKey($mockParentObject, $config, $key)
        );
    }

    /**
     * @test
     */
    public function injectContentObjectRendererSetsObject()
    {
        $mockContentObjectRenderer = $this->getMock(
            ContentObjectRenderer::class
        );
        $this->subject->injectContentObjectRenderer($mockContentObjectRenderer);
        $this->assertAttributeEquals(
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

        $this->assertSame(
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

        $this->assertSame(
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
        $mockObject = $this->getMock(
            DomainObjectInterface::class
        );
        $mockObjectStorage = $this->getMock(
            ObjectStorage::class
        );
        $mockObjectManager = $this->mockObjectManager();

        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will($this->returnValue($mockObjectStorage));

        $this->assertSame(
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
        $config = [
            'field' => 'foo'
        ];
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMock(
            ObjectStorage::class
        );
        $mockObjectStorageFromObject = $this->getMock(
            ObjectStorage::class, ['count', 'current']
        );
        $mockFileReference = $this->getMock(
            FileReference::class
        );
        $this->subject->expects($this->once())
            ->method('getValue')
            ->with($mockObject, $config)
            ->will($this->returnValue($mockObjectStorageFromObject));
        $mockObjectStorageFromObject->expects($this->any())
            ->method('count')
            ->will($this->returnValue(5));
        $mockObjectStorageFromObject->expects($this->any())
            ->method('current')
            ->will($this->returnValue($mockFileReference));
        $mockObjectManager = $this->mockObjectManager();

        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will($this->returnValue($mockObjectStorage));

        $this->assertSame(
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
        $config = ['foo'];
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMock(
            ObjectStorage::class, ['count', 'attach']
        );
        $mockFileReference = $this->getMock(
            FileReference::class
        );
        $this->subject->expects($this->once())
            ->method('getValue')
            ->with($mockObject, $config)
            ->will($this->returnValue($mockFileReference));
        $mockObjectStorage->expects($this->once())
            ->method('attach')
            ->with($mockFileReference);
        $mockObjectStorage->expects($this->any())
            ->method('count')
            ->will($this->returnValue(1));
        $mockObjectManager = $this->mockObjectManager();

        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will($this->returnValue($mockObjectStorage));

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
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMock(
            ObjectStorage::class, ['count', 'attach']
        );
        $mockFile = $this->getMock(
            File::class, [], [], '', false
        );
        $mockFileReference = $this->getMock(
            FileReference::class
        );
        $mockObjectStorage->expects($this->once())
            ->method('attach')
            ->with($mockFileReference);
        $mockObjectStorage->expects($this->any())
            ->method('count')
            ->will($this->returnValue(0));
        $mockObjectManager = $this->mockObjectManager();
        $mockResourceFactory = $this->mockResourceFactory();

        $mockResourceFactory->expects($this->once())
            ->method('getFileObjectByCombinedIdentifier')
            ->with($defaultValue)
            ->will($this->returnValue($mockFile));
        $mockResourceFactory->expects($this->once())
            ->method('createFileReferenceFromFileObject')
            ->with($mockFile)
            ->will($this->returnValue($mockFileReference));
        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will($this->returnValue($mockObjectStorage));

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
        $mockObject = $this->getAccessibleMock(
            AbstractDomainObject::class
        );
        $mockObjectStorage = $this->getMock(
            ObjectStorage::class, ['count', 'attach']
        );
        $mockFile = $this->getMock(
            File::class, [], [], '', false
        );
        $mockFileReference = $this->getMock(
            FileReference::class
        );
        $mockObjectStorage->expects($this->exactly(2))
            ->method('attach')
            ->with($mockFileReference);
        $mockObjectStorage->expects($this->any())
            ->method('count')
            ->will($this->returnValue(0));
        $mockObjectManager = $this->mockObjectManager();
        $mockResourceFactory = $this->mockResourceFactory();

        $mockResourceFactory->expects($this->exactly(2))
            ->method('getFileObjectByCombinedIdentifier')
            ->withConsecutive(
                [$defaultValue], [$alwaysValue]
            )
            ->will($this->returnValue($mockFile));
        $mockResourceFactory->expects($this->exactly(2))
            ->method('createFileReferenceFromFileObject')
            ->with($mockFile)
            ->will($this->returnValue($mockFileReference));
        $mockObjectManager->expects($this->once())
            ->method('get')
            ->with(ObjectStorage::class)
            ->will($this->returnValue($mockObjectStorage));

        $this->subject->getFileStorage($mockObject, $config);
    }
    // todo test default and always values

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected function mockObjectManager()
    {
        $mockObjectManager = $this->getAccessibleMock(
            ObjectManager::class, ['get']
        );
        $this->subject->injectObjectManager($mockObjectManager);

        return $mockObjectManager;
    }

    /**
     * @return mixed
     */
    protected function mockResourceFactory()
    {
        $mockResourceFactory = $this->getMock(
            ResourceFactory::class,
            ['getFileObjectByCombinedIdentifier', 'createFileReferenceFromFileObject']
        );
        $this->subject->injectResourceFactory($mockResourceFactory);

        return $mockResourceFactory;
    }
}
