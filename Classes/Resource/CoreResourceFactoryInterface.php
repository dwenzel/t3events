<?php

namespace DWenzel\T3events\Resource;

use TYPO3\CMS\Core\Resource\FileReference;

/**
 * Interface CoreResourceFactoryInterface
 *
 * Abstraction for TYPO3\CMS\Core\Resource\ResourceFactory to enable unit testing.
 * TYPO3\CMS\Core\Resource\ResourceFactory is a readonly class in TYPO3 v13 and
 * cannot be mocked by PHPUnit without an interface.
 */
interface CoreResourceFactoryInterface
{
    /**
     * Retrieves a file or folder object by its combined identifier.
     */
    public function retrieveFileOrFolderObject(string $input): mixed;

    /**
     * Creates a FileReference object from an array of data.
     */
    /**
     * @param array<string, mixed> $fileReferenceData
     */
    public function createFileReferenceObject(array $fileReferenceData): FileReference;
}
