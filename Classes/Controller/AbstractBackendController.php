<?php

namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Domain\Model\Dto\ModuleData;
use DWenzel\T3events\Service\ModuleDataStorageService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\Exception\UnsupportedRequestTypeException;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AbstractBackendController
 *
 * @package DWenzel\T3events\Controller
 */
abstract class AbstractBackendController extends ActionController
{
    use ModuleDataTrait;

    public function __construct(ModuleDataStorageService $moduleDataStorageService) {
        $this->moduleDataStorageService = $moduleDataStorageService;
    }
    /**
     * Load and persist module data
     */
    public function processRequest(RequestInterface $request): ResponseInterface
    {
        $this->moduleData = $this->moduleDataStorageService->loadModuleData($this->getModuleKey());

        try {
            $response = parent::processRequest($request);
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        } catch (StopActionException $e) {
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
            throw $e;
        }

        return $response;
    }
}
