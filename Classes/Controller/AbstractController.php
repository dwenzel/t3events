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
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Property\Exception as PropertyException;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use Webfox\T3events\Domain\Model\Dto\DemandInterface;
use Webfox\T3events\Domain\Model\Dto\EventDemand;
use Webfox\T3events\Domain\Model\Dto\EventTypeAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use Webfox\T3events\Domain\Model\Dto\SearchAwareDemandInterface;

/**
 * @package t3evetns
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 */
class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {
	use SettingsUtilityTrait, EntityNotFoundHandlerTrait;
	const HANDLE_ENTITY_NOT_FOUND_ERROR = 'handleEntityNotFoundError';

	/**
	 * Request Arguments
	 *
	 * @var \array
	 */
	protected $requestArguments = NULL;

	/*
	 * Referrer Arguments
	 * @var \array
	 */
	protected $referrerArguments = array();

	/**
	 * @var string
	 */
	protected $unknownErrorMessage = 'An unknown error occured.';

	/**
	 * Initialize Action
	 */
	public function initializeAction() {
		$this->setRequestArguments();
		$this->setReferrerArguments();
	}

	/**
	 * Set request arguments
	 *
	 * @return void
	 */
	protected function setRequestArguments() {
		$originalRequestArguments = $this->request->getArguments();
		$action = $originalRequestArguments['action'];
		unset($originalRequestArguments['action']);
		unset($originalRequestArguments['controller']);

		$this->requestArguments = array(
			'action' => $action,
			'pluginName' => $this->request->getPluginName(),
			'controllerName' => $this->request->getControllerName(),
			'extensionName' => $this->request->getControllerExtensionName(),
			'arguments' => $originalRequestArguments,
		);
	}

	/**
	 * Set referrer arguments
	 *
	 * @return void
	 */
	protected function setReferrerArguments() {
		if ($this->request->hasArgument('referrerArguments') AND
			is_array($this->request->getArgument('referrerArguments'))
		) {
			$this->referrerArguments = $this->request->getArgument('referrerArguments');
		} else {
			$this->referrerArguments = array();
		}
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
	 * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
	 * @return void
	 * @throws \Exception
	 * @override \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
	 */
	public function processRequest(RequestInterface $request, ResponseInterface $response) {
		try {
			parent::processRequest($request, $response);
		} catch (\Exception $exception) {
			if (
				($exception instanceof PropertyException\TargetNotFoundException)
				|| ($exception instanceof PropertyException\InvalidSourceException)
			) {
				if ($request instanceof Request) {
					$controllerName = lcfirst($request->getControllerName());
					$actionName = $request->getControllerActionName();
                    if (isset($this->settings[$controllerName][$actionName]['errorHandling'])) {
                        $configuration =  $this->settings[$controllerName][$actionName]['errorHandling'];
                        $this->handleEntityNotFoundError($configuration);
                    }
				}
			}
			throw $exception;
		}
	}

	/**
	 * @param DemandInterface $demand
	 * @param array $overwriteDemand
	 */
	public function overwriteDemandObject(&$demand, $overwriteDemand) {
		if ((bool) $overwriteDemand) {
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

	/**
	 * Creates a search object from given settings
	 *
	 * @param array $searchRequest An array with the search request
	 * @param array $settings Settings for search
	 * @return \Webfox\T3events\Domain\Model\Dto\Search $search
	 */
	public function createSearchObject($searchRequest, $settings) {
		$searchObject = $this->objectManager->get('Webfox\\T3events\\Domain\\Model\\Dto\\Search');

		if (isset($searchRequest['subject']) AND isset($settings['fields'])) {
			$searchObject->setFields($settings['fields']);
			$searchObject->setSubject($searchRequest['subject']);
		}
		if (isset($searchRequest['location']) AND isset($searchRequest['radius'])) {
			$searchObject->setLocation($searchRequest['location']);
			$searchObject->setRadius($searchRequest['radius']);
		}

		return $searchObject;
	}

	/**
	 * Translate a given key
	 *
	 * @param string $key
	 * @param string $extension
	 * @param array $arguments
	 * @codeCoverageIgnore
	 * @return string
	 */
	public function translate($key, $extension = 't3events', $arguments = NULL) {
		$translatedString = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, $extension, $arguments);
		if (is_null($translatedString)) {
			return $key;
		} else {
			return $translatedString;
		}
	}

}
