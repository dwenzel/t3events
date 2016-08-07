<?php
namespace Webfox\T3events\Controller;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use Webfox\T3events\Domain\Repository\AudienceRepository;
use Webfox\T3events\Domain\Repository\EventTypeRepository;
use Webfox\T3events\Domain\Repository\GenreRepository;
use Webfox\T3events\Domain\Repository\VenueRepository;

/**
 * Class AbstractBackendController
 *
 * @package Webfox\T3events\Controller
 */
class AbstractBackendController extends AbstractController
{
    use ModuleDataTrait, DownloadTrait, CompanyRepositoryTrait,
        NotificationRepositoryTrait, CategoryRepositoryTrait;

    /**
     * eventTypeRepository
     *
     * @var \Webfox\T3events\Domain\Repository\EventTypeRepository
     */
    protected $eventTypeRepository;

    /**
     * genreRepository
     *
     * @var \Webfox\T3events\Domain\Repository\GenreRepository
     */
    protected $genreRepository;

    /**
     * audienceRepository
     *
     * @var \Webfox\T3events\Domain\Repository\AudienceRepository
     */
    protected $audienceRepository;

    /**
     * Notification Service
     *
     * @var \Webfox\T3events\Service\NotificationService
     * @inject
     */
    protected $notificationService;

    /**
     * Page uid
     *
     * @var integer
     */
    protected $pageUid = 0;

    /**
     * Persistence Manager
     *
     * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
     * @inject
     */
    protected $persistenceManager;

    /**
     * TsConfig configuration
     *
     * @var array
     */
    protected $tsConfiguration = [];

    /**
     * venueRepository
     *
     * @var \Webfox\T3events\Domain\Repository\VenueRepository
     */
    protected $venueRepository;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $extension = GeneralUtility::camelCaseToLowerCaseUnderscored($this->extensionName);
        $this->setTsConfig($extension);
    }

    /**
     * injectEventTypeRepository
     *
     * @param \Webfox\T3events\Domain\Repository\EventTypeRepository $eventTypeRepository
     * @return void
     */
    public function injectEventTypeRepository(EventTypeRepository $eventTypeRepository)
    {
        $this->eventTypeRepository = $eventTypeRepository;
    }

    /**
     * injectGenreRepository
     *
     * @param \Webfox\T3events\Domain\Repository\GenreRepository $genreRepository
     * @return void
     */
    public function injectGenreRepository(GenreRepository $genreRepository)
    {
        $this->genreRepository = $genreRepository;
    }

    /**
     * injectAudienceRepository
     *
     * @param \Webfox\T3events\Domain\Repository\AudienceRepository $audienceRepository
     * @return void
     */
    public function injectAudienceRepository(AudienceRepository $audienceRepository)
    {
        $this->audienceRepository = $audienceRepository;
    }

    /**
     * injectVenueRepository
     *
     * @param \Webfox\T3events\Domain\Repository\VenueRepository $venueRepository
     * @return void
     */
    public function injectVenueRepository(VenueRepository $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    /**
     * Load and persist module data
     *
     * @param \TYPO3\CMS\Extbase\Mvc\RequestInterface $request
     * @param \TYPO3\CMS\Extbase\Mvc\ResponseInterface $response
     * @return void
     * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
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
     * Get a CSRF token
     *
     * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
     * @return string
     */
    protected function getToken($tokenOnly = false)
    {
        $token = FormProtectionFactory::get()->generateToken('moduleCall', $this->getModuleKey());
        if ($tokenOnly) {
            return $token;
        } else {
            return '&moduleToken=' . $token;
        }
    }

    /**
     * Redirects to alt_doc.php providing a return url
     *
     * @param string $action
     * @param string $table
     * @param int $uid
     * @param string $module
     */
    protected function redirectToEditAction($action, $table, $uid, $module)
    {
        $pid = $this->pageUid;
        if ($pid === 0) {
            if (isset($this->tsConfiguration['defaultPid.'])
                && is_array($this->tsConfiguration['defaultPid.'])
                && isset($this->tsConfiguration['defaultPid.'][$table])
            ) {
                $pid = (int)$this->tsConfiguration['defaultPid.'][$table];
            }
        }
        $returnUrl = 'mod.php?M=' . $module . '&id=' . $this->pageUid;
        $returnUrl .= '&moduleToken=' . FormProtectionFactory::get()->generateToken('moduleCall', $module);
        $url = 'alt_doc.php?edit[' . $table . '][' . $pid . ']=' . $action . '&returnUrl=' . urlencode($returnUrl);
        HttpUtility::redirect($url);
    }


    /**
     * Set the TsConfig configuration for the extension
     *
     * @param string $extensionName
     * @return void
     */
    protected function setTsConfig($extensionName)
    {
        $tsConfig = BackendUtility::getPagesTSconfig($this->pageUid);
        if (isset($tsConfig[$extensionName . '.']['module.']) && is_array($tsConfig[$extensionName . '.']['module.'])) {
            $this->tsConfiguration = $tsConfig[$extensionName . '.']['module.'];
        }
    }
}
