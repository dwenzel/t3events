<?php

namespace DWenzel\T3events\Controller\Backend;

use DWenzel\T3events\Controller\PerformanceController;
use DWenzel\T3events\Controller\SettingsUtilityTrait;
use DWenzel\T3events\Utility\SettingsInterface as SI;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotException;
use TYPO3\CMS\Extbase\SignalSlot\Exception\InvalidSlotReturnException;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;

/**
 * Class ScheduleController
 */
class ScheduleController extends PerformanceController
{
    use ModuleDataTrait, FormTrait, SettingsUtilityTrait;

    /**
     * Forwards the request to another action and / or controller.
     *
     * Request is directly transferred to the other action / controller
     * without the need for a new request.
     *
     * @param string $actionName Name of the action to forward to
     * @param string|null $controllerName Unqualified object name of the controller to forward to. If not specified, the current controller is used.
     * @param string|null $extensionName Name of the extension containing the controller to forward to. If not specified, the current extension is assumed.
     * @param array|null $arguments Arguments to pass to the target action
     * @throws StopActionException
     * @see redirect()
     */
     public function forward($actionName, $controllerName = null, $extensionName = null, array $arguments = null)
     {
         return parent::forward($actionName, $controllerName, $extensionName, $arguments);
     }

     
    /**
     * Load and persist module data
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return void
     * @throws \Exception
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        $this->moduleData = $this->moduleDataStorageService->loadModuleData($this->getModuleKey());

        parent::processRequest($request, $response);
        $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
    }

    /**
     * action list
     *
     * @param array $overwriteDemand
     * @return void
     * @throws InvalidSlotException
     * @throws InvalidSlotReturnException
     */
    public function listAction(array $overwriteDemand = null)
    {
        $demand = $this->performanceDemandFactory->createFromSettings($this->settings);
        $filterOptions = $this->getFilterOptions(
            $this->settings['filter']
        );

        if ($overwriteDemand === null) {
            $overwriteDemand = $this->moduleData->getOverwriteDemand();
        } else {
            $this->moduleData->setOverwriteDemand($overwriteDemand);
        }

        $this->overwriteDemandObject($demand, $overwriteDemand);

        $templateVariables = [
            'debug' => $this->settings['debug'],
            'performances' => $this->performanceRepository->findDemanded($demand),
            SI::OVERWRITE_DEMAND => $overwriteDemand,
            'demand' => $demand,
            SI::SETTINGS => $this->settings,
            'filterOptions' => $filterOptions,
        ];

        $this->emitSignal(__CLASS__, self::PERFORMANCE_LIST_ACTION, $templateVariables);
        $this->view->assignMultiple($templateVariables);
    }
}
