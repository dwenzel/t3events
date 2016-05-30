<?php
namespace Webfox\T3events\Controller;

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
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;
use Webfox\T3events\Domain\Model\Dto\EventDemand;
use Webfox\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\SearchAwareDemandInterface;

/**
 * Class AbstractController
 *
 * @package Webfox\T3events\Controller
 * @deprecated Use controller traits instead
 */
class AbstractController extends ActionController
{
    use SettingsUtilityTrait, EntityNotFoundHandlerTrait, TranslateTrait, SearchTrait;

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
    protected $unknownErrorMessage = 'An unknown error occured.';

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

    /**
     * @param DemandInterface $demand
     * @param array $overwriteDemand
     */
    public function overwriteDemandObject(&$demand, $overwriteDemand)
    {
        if ((bool)$overwriteDemand) {
            foreach ($overwriteDemand as $propertyName => $propertyValue) {
                switch ($propertyName) {
                    case 'sortBy':
                        $orderings = $propertyValue;
                        if (isset($overwriteDemand['sortDirection'])) {
                            $orderings .= '|' . $overwriteDemand['sortDirection'];
                        }
                        $demand->setOrder($orderings);
                        $demand->setSortBy($overwriteDemand['sortBy']);
                        break;
                    case 'search':
                        if ($demand instanceof SearchAwareDemandInterface) {
                            $controllerKey = $this->settingsUtility->getControllerKey($this);
                            $searchObj = $this->createSearchObject(
                                $propertyValue,
                                $this->settings[$controllerKey]['search']
                            );
                            $demand->setSearch($searchObj);
                        }
                        break;
                    case 'genres':
                        if ($demand instanceof EventDemand) {
                            $demand->setGenre($propertyValue);
                        }
                        if ($demand instanceof GenreAwareDemandInterface) {
                            $demand->setGenres($propertyValue);
                        }
                        break;
                    case 'eventTypes':
                        if ($demand instanceof EventDemand) {
                            $demand->setEventType($propertyValue);
                        }
                        if ($demand instanceof EventTypeAwareDemandInterface) {
                            $demand->setEventTypes($propertyValue);
                        }
                        break;
                    case 'sortDirection':
                        if ($propertyValue !== 'desc') {
                            $propertyValue = 'asc';
                        }
                    // fall through to default
                    default:
                        if (ObjectAccess::isPropertySettable($demand, $propertyName)) {
                            ObjectAccess::setProperty($demand, $propertyName, $propertyValue);
                        }
                }
            }
        }
    }
}
