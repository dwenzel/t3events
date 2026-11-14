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

    protected ModuleDataStorageService $moduleDataStorageService;

    /** @var array<string, mixed> */
    protected array $settings;

    protected int $pageUid = 0;

    /**
     * @return array<mixed>
     */
    abstract public function mergeSettings(): array;

    abstract public function getModuleKey(): string;

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
        /** @var ServerRequestInterface $typo3Request */
        $typo3Request = $GLOBALS['TYPO3_REQUEST'];
        $this->pageUid = (int)($typo3Request->getQueryParams()['id'] ?? null);
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

    public function getModuleData(): ModuleData
    {
        return $this->moduleData;
    }

    public function setModuleData(ModuleData $moduleData): void
    {
        $this->moduleData = $moduleData;
    }

}
