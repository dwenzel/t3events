<?php

/**
 * This file is part of the "Events" project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace DWenzel\T3events;

/**
 * Trait for static method calls
 *
 * This is useful to make static method calls mock-able in tests.
 *
 * This trait must not be used more than once in a class hierarchy,
 * otherwise endless call loops occur for parent method calls.
 * See https://bugs.php.net/bug.php?id=48770 for details.
 */
trait CallStaticTrait
{
    /**
     * Performs a static method call
     *
     * Note that parent::class should be used instead of 'parent'
     * to refer to the actual parent class.
     *
     * @param string $classAndMethod Name of the class
     * @param string $methodName Name of the method
     * @param mixed $parameter,... Parameters to the method
     * @return mixed
     */
    protected function callStatic($className, $methodName)
    {
        $parameters = func_get_args();
        $parameters = array_slice($parameters, 2); // Remove $className and $methodName

        return call_user_func_array($className . '::' . $methodName, $parameters);
    }
}
