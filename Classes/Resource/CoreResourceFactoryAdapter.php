<?php

declare(strict_types=1);

namespace DWenzel\T3events\Resource;

use TYPO3\CMS\Core\Resource\FileReference;
use TYPO3\CMS\Core\Resource\ResourceFactory as Typo3ResourceFactory;

/**
 * Adapter wrapping TYPO3\CMS\Core\Resource\ResourceFactory to implement
 * CoreResourceFactoryInterface. Required because the core class is readonly
 * in TYPO3 v13 and cannot be mocked or aliased directly without an adapter.
 */
final class CoreResourceFactoryAdapter implements CoreResourceFactoryInterface
{
    public function __construct(private readonly Typo3ResourceFactory $resourceFactory)
    {
    }

    public function retrieveFileOrFolderObject(string $input): mixed
    {
        return $this->resourceFactory->retrieveFileOrFolderObject($input);
    }

    /**
     * @param array<string, mixed> $fileReferenceData
     */
    public function createFileReferenceObject(array $fileReferenceData): FileReference
    {
        return $this->resourceFactory->createFileReferenceObject($fileReferenceData);
    }
}
