<?php

namespace DWenzel\T3events\Controller;

use DWenzel\T3events\CallStaticTrait;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;

/**
 * Class AbstractBackendController
 *
 * @package DWenzel\T3events\Controller
 */
class AbstractBackendController extends AbstractController
{
    use AudienceRepositoryTrait, CallStaticTrait, CategoryRepositoryTrait, CompanyRepositoryTrait,
        DownloadTrait, EventTypeRepositoryTrait, GenreRepositoryTrait,
        ModuleDataTrait, NotificationRepositoryTrait, NotificationServiceTrait,
        PersistenceManagerTrait, VenueRepositoryTrait;

    /**
     * Page uid
     *
     * @var integer
     */
    protected $pageUid = 0;

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

    /**
     * Get frontend base url as configured in TypoScript
     * Pass this as a variable when rendering fluid templates in Backend context for instance
     * if you want to render images in emails.
     *
     * @return string
     */
    protected function getBaseUrlForFrontend()
    {
        $typoScriptConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);

        return $typoScriptConfiguration['config.']['baseURL'];
    }

    /**
     * Redirect to tceform creating a new record
     *
     * @param string $table table name
     */
    protected function redirectToCreateNewRecord($table)
    {
        $returnUrl = 'index.php?M=' . $this->getModuleKey() . '&id=' . $this->pageUid . $this->getToken();
        $url = $this->callStatic(
            BackendUtility::class, 'getModuleUrl',
            'record_edit',
            [
                'edit[' . $table . '][' . $this->pageUid . ']' => 'new',
                'returnUrl' => $returnUrl
            ]);
        $this->callStatic(HttpUtility::class, 'redirect', $url);
    }

    /**
     * Get a CSRF token
     *
     * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
     * @return string
     */
    protected function getToken($tokenOnly = false)
    {
        $factory = $this->callStatic(FormProtectionFactory::class, 'get');

        $token = $factory->generateToken('moduleCall', $this->getModuleKey());
        if ($tokenOnly) {
            return $token;
        }

        return '&moduleToken=' . $token;
    }
}
