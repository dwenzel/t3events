<?php

namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Controller\Backend\BackendModuleViewTrait;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

/**
 * Class AbstractBackendController
 *
 * @package DWenzel\T3events\Controller
 */
abstract class AbstractBackendController extends ActionController
{
    use BackendModuleViewTrait;

    public function processRequest(RequestInterface $request): ResponseInterface
    {
        /*$this->moduleData = $this->moduleDataStorageService->loadModuleData(...);

        try {
            $response = parent::processRequest($request);
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        } catch (StopActionException $e) {
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
            throw $e;
        }

        return $response;*/
        return parent::processRequest($request);
    }
}
