<?php
namespace DWenzel\T3events\Resource;

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
use TYPO3\CMS\Core\Resource\FileInterface;
use TYPO3\CMS\Core\Resource\ResourceFactory as CoreResourceFactory;
use TYPO3\CMS\Core\Resource\File;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

/**
 * Class ResourceFactory
 *
 * @package DWenzel\T3events\Resource
 */
class ResourceFactory extends CoreResourceFactory
{
    use ObjectManagerTrait;

    /**
     * Gets a file by combined identifier using the
     * resource factory's method.
     * Returns null if no file or a folder was found!
     *
     * @param $identifier
     * @return null|\TYPO3\CMS\Core\Resource\FileInterface
     */
    public function getFileObjectByCombinedIdentifier($identifier)
    {
        $file = $this->retrieveFileOrFolderObject(
            $identifier
        );
        if ($file instanceof FileInterface) {
            return $file;
        }

        return null;
    }

    /**
     * Creates a new (extbase) file reference from a given file object
     *
     * @param FileInterface|File $file
     * @return \TYPO3\CMS\Extbase\Domain\Model\FileReference
     */
    public function createFileReferenceFromFileObject(File $file)
    {
        $coreFileReference =  $this->createFileReferenceObject(
            [
                'uid_local' => $file->getUid(),
                'uid_foreign' => uniqid('NEW_'),
                'uid' => uniqid('NEW_'),
            ]
        );
        /** @var \TYPO3\CMS\Extbase\Domain\Model\FileReference $fileReference */
        $fileReference = $this->objectManager->get(
            FileReference::class
        );
        $fileReference->setOriginalResource($coreFileReference);

        return $fileReference;
    }
}
