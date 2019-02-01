<?php

namespace DWenzel\T3events\Controller;

use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;

/**
 * Class AbstractBackendController
 *
 * @package DWenzel\T3events\Controller
 */
class AbstractBackendController extends ActionController
{
    /**
     * Load and persist module data
     *
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException
     */
    public function processRequest(RequestInterface $request, ResponseInterface $response)
    {
        $this->moduleData = $this->moduleDataStorageService->loadModuleData($this->getModuleKey());

        try {
            parent::processRequest($request, $response);
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        } catch (StopActionException $e) {
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
            throw $e;
        }
    }
}
