<?php
namespace DWenzel\T3events\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use DWenzel\T3events\Domain\Model\Dto\ModuleData;
use DWenzel\T3events\Service\ModuleDataStorageService;
use TYPO3\CMS\Extbase\Http\ForwardResponse;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;

/**
 * Class ModuleDataTrait
 * Provides functionality for backend module controller
 *
 * @package DWenzel\T3events\Controller
 */
trait ModuleDataTrait
{
    protected ModuleData $moduleData;

    protected ModuleDataStorageService $moduleDataStorageService;

    /**
     * @return array
     */
    abstract public function mergeSettings();

    /**
     * @return string
     */
    abstract public function getModuleKey();

    public function processRequest(RequestInterface $request): ResponseInterface
    {
        $this->moduleData = $this->moduleDataStorageService->loadModuleData($this->getModuleKey());

        try {
            $response = parent::processRequest($request);
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        } catch (\Exception $e) {
            $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
            throw $e;
        }

        return $response;
    }

    /**
     * initializes all action methods
     */
    public function initializeAction(): void
    {
        $this->pageUid = (int)($GLOBALS['TYPO3_REQUEST']->getQueryParams()['id'] ?? null);
        $this->settings = $this->mergeSettings();
    }

    /**
     * Reset action
     * Resets all module data and forwards the request to the list action
     */
    public function resetAction(): ResponseInterface
    {
        $this->moduleData = GeneralUtility::makeInstance(ModuleData::class);
        $this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        return new ForwardResponse('list');
    }

    /**
     * @return ModuleData
     */
    public function getModuleData()
    {
        return $this->moduleData;
    }

    public function setModuleData(ModuleData $moduleData): void
    {
        $this->moduleData = $moduleData;
    }

}
