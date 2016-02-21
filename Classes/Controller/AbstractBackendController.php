<?php
namespace Webfox\T3events\Controller;

use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Resource\Driver\LocalDriver;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Web\Response;
use Webfox\T3events\Domain\Model\Dto\ModuleData;
use \TYPO3\CMS\Backend\Utility\BackendUtility;
use Webfox\T3events\Domain\Repository\AbstractDemandedRepository;
use Webfox\T3events\Domain\Repository\CompanyRepository;
use Webfox\T3events\Domain\Repository\EventTypeRepository;
use Webfox\T3events\Domain\Repository\GenreRepository;
use Webfox\T3events\Domain\Repository\AudienceRepository;
use Webfox\T3events\Domain\Repository\VenueRepository;
use Webfox\T3events\InvalidFileTypeException;
use Webfox\T3events\Service\ModuleDataStorageService;

/**
 * Class AbstractBackendController
 *
 * @package Webfox\T3events\Controller
 */
class AbstractBackendController extends AbstractController {

	/**
	 * Company Repository
	 *
	 * @var \Webfox\T3events\Domain\Repository\CompanyRepository
	 * @inject
	 */
	protected $companyRepository = null;

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
	 * @var \Webfox\T3events\Domain\Model\Dto\ModuleData
	 */
	protected $moduleData;

	/**
	 * Notification Repository
	 *
	 * @var \Webfox\T3events\Domain\Repository\NotificationRepository
	 * @inject
	 */
	protected $notificationRepository = null;

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
	 * @var \Webfox\T3events\Service\ModuleDataStorageService
	 */
	protected $moduleDataStorageService;

	/**
	 * @var \TYPO3\CMS\Core\Resource\Driver\LocalDriver
	 */
	protected $localDriver;

	/**
	 * venueRepository
	 *
	 * @var \Webfox\T3events\Domain\Repository\VenueRepository
	 */
	protected $venueRepository;

	/**
	 * Constructor
	 */
	public function __construct() {
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
	public function injectEventTypeRepository(EventTypeRepository $eventTypeRepository) {
		$this->eventTypeRepository = $eventTypeRepository;
	}

	/**
	 * injectCompanyRepository
	 *
	 * @param \Webfox\T3events\Domain\Repository\CompanyRepository $companyRepository
	 * @return void
	 */
	public function injectCompanyRepository(CompanyRepository $companyRepository) {
		$this->companyRepository = $companyRepository;
	}

	/**
	 * injectGenreRepository
	 *
	 * @param \Webfox\T3events\Domain\Repository\GenreRepository $genreRepository
	 * @return void
	 */
	public function injectGenreRepository(GenreRepository $genreRepository) {
		$this->genreRepository = $genreRepository;
	}

	/**
	 * injectAudienceRepository
	 *
	 * @param \Webfox\T3events\Domain\Repository\AudienceRepository $audienceRepository
	 * @return void
	 */
	public function injectAudienceRepository(AudienceRepository $audienceRepository) {
		$this->audienceRepository = $audienceRepository;
	}

	/**
	 * injects the module data storage service
	 *
	 * @param ModuleDataStorageService $service
	 */
	public function injectModuleDataStorageService(ModuleDataStorageService $service) {
		$this->moduleDataStorageService = $service;
	}

	/**
	 * Injects the local driver for file system
	 *
	 * @param \TYPO3\CMS\Core\Resource\Driver\LocalDriver $localDriver
	 */
	public function injectLocalDriver(LocalDriver $localDriver) {
		$this->localDriver = $localDriver;
	}

	/**
	 * injectVenueRepository
	 *
	 * @param \Webfox\T3events\Domain\Repository\VenueRepository $venueRepository
	 * @return void
	 */
	public function injectVenueRepository(VenueRepository $venueRepository) {
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
	public function processRequest(RequestInterface $request, ResponseInterface $response) {
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
	 * initialize action
	 */
	public function initializeAction() {
		$this->pageUid = (int) GeneralUtility::_GET('id');
	}

	/**
	 * Gets the module key
	 *
	 * @return string
	 */
	protected function getModuleKey() {
		return $GLOBALS['moduleName'];
	}

	/**
	 * Reset action
	 * Resets all module data and forwards the request to the list action
	 *
	 * @throws \TYPO3\CMS\Extbase\Mvc\Exception\StopActionException
	 */
	public function resetAction() {
		$this->moduleData = $this->objectManager->get(ModuleData::class);
		$this->moduleDataStorageService->persistModuleData($this->moduleData, $this->getModuleKey());
		$this->forward('list');
	}

	/**
	 * Gets a sanitized filename for download
	 *
	 * @param string $fileName
	 * @param $prependDate
	 * @return string
	 * @throws \TYPO3\CMS\Core\Resource\Exception\InvalidFileNameException
	 */
	public function getDownloadFileName($fileName, $prependDate = TRUE) {
		if ($prependDate) {
			$fileName = date('Y-m-d_H-m') . '_' . $fileName;
		}

		return $this->localDriver->sanitizeFileName($fileName);
	}

	/**
	 * Get a CSRF token
	 *
	 * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
	 * @return string
	 */
	protected function getToken($tokenOnly = FALSE) {
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
	protected function redirectToEditAction($action, $table, $uid, $module) {
		$pid = $this->pageUid;
		if ($pid === 0) {
			if (isset($this->tsConfiguration['defaultPid.'])
				&& is_array($this->tsConfiguration['defaultPid.'])
				&& isset($this->tsConfiguration['defaultPid.'][$table])
			) {
				$pid = (int) $this->tsConfiguration['defaultPid.'][$table];
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
	protected function setTsConfig($extensionName) {
		$tsConfig = BackendUtility::getPagesTSconfig($this->pageUid);
		if (isset($tsConfig[$extensionName . '.']['module.']) && is_array($tsConfig[$extensionName . '.']['module.'])) {
			$this->tsConfiguration = $tsConfig[$extensionName . '.']['module.'];
		}
	}

	/**
	 * Sends download headers
	 *
	 * @param string $ext
	 * @param string $fileName
	 * @throws InvalidFileTypeException
	 */
	protected function sendDownloadHeaders($ext, $fileName) {
		switch ($ext) {
			case 'csv':
				$cType = 'text/csv';
				break;
			case 'txt':
				$cType = 'text/plain';
				break;
			case 'pdf':
				$cType = 'application/pdf';
				break;
			case 'exe':
				$cType = 'application/octet-stream';
				break;
			case 'zip':
				$cType = 'application/zip';
				break;
			case 'doc':
				$cType = 'application/msword';
				break;
			case 'xls':
				$cType = 'application/vnd.ms-excel';
				break;
			case 'ppt':
				$cType = 'application/vnd.ms-powerpoint';
				break;
			case 'gif':
				$cType = 'image/gif';
				break;
			case 'png':
				$cType = 'image/png';
				break;
			case 'jpeg':
			case 'jpg':
				$cType = 'image/jpg';
				break;
			case 'mp3':
				$cType = 'audio/mpeg';
				break;
			case 'wav':
				$cType = 'audio/x-wav';
				break;
			case 'mpeg':
			case 'mpg':
			case 'mpe':
				$cType = 'video/mpeg';
				break;
			case 'mov':
				$cType = 'video/quicktime';
				break;
			case 'avi':
				$cType = 'video/x-msvideo';
				break;

			//forbidden filetypes
			case 'inc':
			case 'conf':
			case 'sql':
			case 'cgi':
			case 'htaccess':
			case 'php':
			case 'php3':
			case 'php4':
			case 'php5':
				throw new InvalidFileTypeException(
					'Invalid file type ' . $ext . ' for download with file name ' . $fileName,
					1456009720
				);

			default:
				$cType = 'application/force-download';
				break;
		}

		$headers = [
			'Pragma' => 'public',
			'Expires' => 0,
			'Cache-Control' => 'public',
			'Content-Description' => 'File Transfer',
			'Content-Type' => $cType,
			'Content-Disposition' => 'attachment; filename="' . $fileName . '.' . $ext . '"',
			'Content-Transfer-Encoding' => 'binary',
		];
		if (!$this->response instanceof Response) {
			$this->response = $this->objectManager->get(Response::class);
		}
		foreach ($headers as $header => $data) {
			$this->response->setHeader($header, $data);
		}
		$this->response->sendHeaders();
	}

	/**
	 * Gets filter options for view template
	 *
	 * @param array $settings
	 * @return array
	 */
	protected function getFilterOptions($settings) {
		$filterOptions = [];
		foreach ($settings as $key => $value) {
			$propertyName = lcfirst($key) . 'Repository';
			if (property_exists(get_class($this), $propertyName)
				&& $this->{$propertyName} instanceof AbstractDemandedRepository
			) {
				/** @var AbstractDemandedRepository $repository */
				$repository = $this->{$propertyName};
				if (!empty($value)) {
					$result = $repository->findMultipleByUid($value, 'title');
				} else {
					$result = $repository->findAll();
				}
				$filterOptions[$key . 's'] = $result;
			}
			if ($key === 'periods') {
				$periodOptions = [];
				$periodEntries = ['futureOnly', 'pastOnly', 'all', 'specific'];
				if (!empty($value)) {
					$periodEntries = GeneralUtility::trimExplode(',', $value, TRUE);
				}
				foreach ($periodEntries as $entry) {
					$period = new \stdClass();
					$period->key = $entry;
					$period->value = $this->translate('label.period.' . $entry, 't3events');
					$periodOptions[] = $period;
				}
				$filterOptions['periods'] = $periodOptions;
			}
		}


		return $filterOptions;
	}
}
