<?php

declare(strict_types=1);

namespace DWenzel\T3events\Fixture;

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 */

use TYPO3\CMS\Core\Core\ApplicationContext;

/**
 * Decides whether fixtures may be seeded or purged on this system.
 *
 * Allowed: the root context is `Development` or `Testing`, or any sub-context
 * is `Staging` (e.g. `Production/Staging`). Everything else is refused.
 * `ApplicationContext::isProduction()` alone is not enough: it is also true
 * for `Production/Staging`, which is allowed.
 */
class ApplicationContextGuard
{
    public const EXCEPTION_CODE = 1763215001;

    protected const ALLOWED_ROOT_CONTEXTS = ['Development', 'Testing'];
    protected const ALLOWED_SUB_CONTEXT = 'Staging';

    public function isAllowed(ApplicationContext $context): bool
    {
        $parts = explode('/', (string)$context);

        if (in_array($parts[0], self::ALLOWED_ROOT_CONTEXTS, true)) {
            return true;
        }

        return in_array(self::ALLOWED_SUB_CONTEXT, array_slice($parts, 1), true);
    }

    /**
     * @throws \RuntimeException if the context is not allowed
     */
    public function assertAllowed(ApplicationContext $context): void
    {
        if ($this->isAllowed($context)) {
            return;
        }

        throw new \RuntimeException(
            sprintf(
                'Fixtures are not seeded or purged in application context "%s". Allowed are the'
                . ' contexts Development and Testing and any context with the sub-context Staging'
                . ' (e.g. Production/Staging).',
                (string)$context
            ),
            self::EXCEPTION_CODE
        );
    }
}
