<?php
namespace DWenzel\T3events\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
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

    protected ?ModuleDataStorageService $moduleDataStorageService = null;

    /**
     * @return array<mixed>
     */
    abstract public function mergeSettings(): array;

    abstract public function getModuleKey(): string;

    public function injectModuleDataStorageService(ModuleDataStorageService $moduleDataStorageService): void
    {
        $this->moduleDataStorageService = $moduleDataStorageService;
    }

    protected function callActionMethod(RequestInterface $request): ResponseInterface
    {
        $moduleDataStorageService = $this->moduleDataStorageService ?? GeneralUtility::makeInstance(ModuleDataStorageService::class);
        $this->moduleData = $moduleDataStorageService->loadModuleData($this->getModuleKey());
        $response = parent::callActionMethod($request);
        $moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        return $response;
    }

    /**
     * initializes all action methods
     */
    public function initializeAction(): void
    {
        /** @var ServerRequestInterface $typo3Request */
        $typo3Request = $GLOBALS['TYPO3_REQUEST'];
        $this->pageUid = (int)($typo3Request->getQueryParams()['id'] ?? null);
        $this->settings = $this->mergeSettings();
    }

    /**
     * Reset action
     * Resets all module data and forwards the request to the list action
     */
    public function resetAction()
    {
        $this->moduleData = GeneralUtility::makeInstance(ModuleData::class);
        $moduleDataStorageService = $this->moduleDataStorageService ?? GeneralUtility::makeInstance(ModuleDataStorageService::class);
        $moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
        return new ForwardResponse('list');
    }

    public function getModuleData(): ModuleData
    {
        return $this->moduleData;
    }

    public function setModuleData(ModuleData $moduleData): void
    {
        $this->moduleData = $moduleData;
    }

}
