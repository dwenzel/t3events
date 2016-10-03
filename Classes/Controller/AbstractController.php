<?php
namespace DWenzel\T3events\Controller;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class AbstractController
 *
 * @package DWenzel\T3events\Controller
 * @deprecated Use controller traits instead
 */
class AbstractController extends ActionController
{
    use SettingsUtilityTrait, EntityNotFoundHandlerTrait, TranslateTrait,
        SearchTrait, DemandTrait;

    /**
     * Request Arguments
     *
     * @var array
     */
    protected $requestArguments = null;

    /*
     * Referrer Arguments
     * @var array
     */
    protected $referrerArguments = [];

    /**
     * @var string
     */
    protected $unknownErrorMessage = 'An unknown error occurred.';

    /**
     * Initialize Action
     */
    public function initializeAction()
    {
        $this->setRequestArguments();
        $this->setReferrerArguments();
    }

    /**
     * Set request arguments
     *
     * @return void
     */
    protected function setRequestArguments()
    {
        $originalRequestArguments = $this->request->getArguments();
        $action = $originalRequestArguments['action'];
        unset($originalRequestArguments['action']);
        unset($originalRequestArguments['controller']);

        $this->requestArguments = [
            'action' => $action,
            'pluginName' => $this->request->getPluginName(),
            'controllerName' => $this->request->getControllerName(),
            'extensionName' => $this->request->getControllerExtensionName(),
            'arguments' => $originalRequestArguments,
        ];
    }

    /**
     * Set referrer arguments
     *
     * @return void
     */
    protected function setReferrerArguments()
    {
        if ($this->request->hasArgument('referrerArguments') AND
            is_array($this->request->getArgument('referrerArguments'))
        ) {
            $this->referrerArguments = $this->request->getArgument('referrerArguments');
        } else {
            $this->referrerArguments = [];
        }
    }


}
