<?php

declare(strict_types=1);

namespace TYPO3\CMS\Extbase\Mvc\View;

/**
 * Compatibility shim: ViewInterface was removed in TYPO3 v12.
 * @deprecated Only for test compatibility.
 */
interface ViewInterface
{
    public function assign(string $key, mixed $value): self;
    public function assignMultiple(array $values): self;
    public function render(string $actionName = null): string;
}
