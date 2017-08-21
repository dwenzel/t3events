<?php
namespace DWenzel\T3events\Utility;

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
use DWenzel\T3events\Object\ObjectManagerTrait;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;

use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use DWenzel\T3events\Resource\ResourceFactory;

/**
 * Class SettingsUtility
 *
 * @package DWenzel\T3events\Utility
 */
class SettingsUtility implements SingletonInterface
{
    use ObjectManagerTrait;

    /**
     * @var \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer
     */
    protected $contentObjectRenderer;

    /**
     * @var array
     */
    protected $controllerKeys = [];

     /**
     * @var ResourceFactory
     */
    protected $resourceFactory;

    /**
     * injects the ContentObjectRenderer
     *
     * @param ContentObjectRenderer $contentObjectRenderer
     */
    public function injectContentObjectRenderer(ContentObjectRenderer $contentObjectRenderer)
    {
        $this->contentObjectRenderer = $contentObjectRenderer;
    }

    /**
     * injects the ResourceFactory
     *
     * @param \DWenzel\T3events\Resource\ResourceFactory $resourceFactory
     */
    public function injectResourceFactory(ResourceFactory $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * Gets a value by key either from settings or from a given object
     *
     * If $config[$key]['field'] is set to string this string
     * is interpreted as property path of the object and we try to
     * get it from the object
     * If $config[$key]['default'] is set and no value can be fetched from
     * object we return the default value
     * If $config[$key] is a string we return this
     * If all above fails we return null.
     *
     * @param object|array $object
     * @param array $config
     * @param string $key
     * @return mixed
     * @deprecated Replace by $this->getValue()
     */
    public function getValueByKey($object, $config, $key)
    {
        $value = null;
        if (isset($config[$key])) {
            $value = $this->getValue($object, $config[$key]);
        }

        return $value;
    }

    /**
     * Gets a settings key for a controller
     *
     * @param object $controller
     * @return string
     */
    public function getControllerKey($controller)
    {
        $className = get_class($controller);
        if (isset($this->controllerKeys[$className])) {
            $controllerKey = $this->controllerKeys[$className];
        } else {
            $controllerKey = lcfirst(str_replace('Controller', '', end(explode('\\', $className))));
            $this->controllerKeys[$className] = $controllerKey;
        }

        return $controllerKey;
    }

    /**
     * Gets an ObjectStorage with FileReference objects from TypoScript settings.
     * Configuration examples:
     * $config[
     *    'field' => 'property.path.of.object',
     *    'default' => '1,EXT:extension_name/path/to/file' // optional list of combined file identifiers
     *    'always' => 'file:1,path/to/file' // optional list of combined file identifiers
     * ]
     * 1. Object contains files:
     * IF object contains an ObjectStorage at the property path given in $config['field']
     * AND the ObjectStorage is not empty
     * AND the ObjectStorage contains FileReference objects
     * THEN these FileReferences are added to the resulting ObjectStorage
     * 2. Object does not contain files but default files are found:
     * IF object does NOT contain an ObjectStorage at the property path given in $config['field']
     * OR the property does NOT exist
     * OR the property contains an empty ObjectStorage
     * AND (at least one of) the files given in $config['default'] is found
     * THEN they are added to the resulting ObjectStorage
     * 3. Files which should always be added are found
     * IF (at least one of) the files given in $config['always'] is found
     * THEN they are added to the resulting ObjectStorage
     *
     * @param DomainObjectInterface $object
     * @param array $config TypoScript configuration array for look up
     * @return ObjectStorage
     */
    public function getFileStorage(DomainObjectInterface $object, $config)
    {
        $fileStorage = $this->objectManager->get(
            ObjectStorage::class
        );
        $valueFromSettings = $this->getValue($object, $config);
        // got ObjectStorage from field
        if ($valueFromSettings instanceof ObjectStorage) {
            $valueFromSettings->rewind();
            if ($valueFromSettings->current() instanceof FileReference) {
                $fileStorage = $valueFromSettings;
            }
        }

        // got FileReference from field
        if ($valueFromSettings instanceof FileReference) {
            $fileStorage->attach($valueFromSettings);
        }
        // should add File object from field?

        // omit default value
        if ($fileStorage->count() > 0) {
            $valueFromSettings = '';
        }

        // add always
        // should 'always' be added to this->getValue()?
        if (isset($config['always']) && is_string($config['always'])) {
            if (!is_string($valueFromSettings)) {
                $valueFromSettings = '';
            }
            $valueFromSettings = $valueFromSettings . ',' . $config['always'];
        }

        if (is_string($valueFromSettings) && $valueFromSettings !== '') {
            $combinedIdentifiers = GeneralUtility::trimExplode(',', $valueFromSettings, true);
            foreach ($combinedIdentifiers as $fileId) {
                $file = $this->resourceFactory->getFileObjectByCombinedIdentifier($fileId);
                if ($file === null) {
                    continue;
                }
                $fileReference = $this->resourceFactory->createFileReferenceFromFileObject($file);
                $fileStorage->attach($fileReference);
            }
        }

        return $fileStorage;
    }

    /**
     * Gets a value either from settings or from a given object
     * If $config['field'] is set to string this string
     * is interpreted as property path of the object and we try to
     * get it from the object
     * If $config['default'] is set and no value can be fetched from
     * object we return the default value
     * If $config is a string we return this
     * If all above fails we return null.
     *
     * @param object|array $object
     * @param array|string $config
     * @return mixed|string
     */
    public function getValue($object, $config)
    {
        $value = null;

        if (isset($config['field']) && is_string($config['field'])) {
            $value = ObjectAccess::getPropertyPath($object, $config['field']);
            if (isset($config['noTrimWrap'])) {
                $value = $this->contentObjectRenderer->noTrimWrap($value, $config['noTrimWrap']);
            }
        }
        if ($value === null && isset($config['default'])) {
            $value = $config['default'];
        }

        if ($value === null && is_string($config)) {
            $value = $config;
        }

        return $value;
    }
}
