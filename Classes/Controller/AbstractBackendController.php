<?php
namespace Webfox\T3events\Controller;

use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\HttpUtility;
use TYPO3\CMS\Extbase\Mvc\Exception\StopActionException;
use TYPO3\CMS\Extbase\Mvc\RequestInterface;
use TYPO3\CMS\Extbase\Mvc\ResponseInterface;
use Webfox\T3events\Domain\Model\Dto\ModuleData;
use \TYPO3\CMS\Backend\Utility\BackendUtility;
use Webfox\T3events\Service\ModuleDataStorageService;

/**
 * Class AbstractBackendController
 *
 * @package Webfox\T3events\Controller
 */
class AbstractBackendController extends AbstractController {

	/**
	 * @var \Webfox\T3events\Domain\Model\Dto\ModuleData
	 */
	protected $moduleData;

	/**
	 * Page uid
	 *
	 * @var integer
	 */
	protected $pageUid = 0;

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
	 * Constructor
	 */
	public function __construct() {
		parent::__construct();
		$extension = GeneralUtility::camelCaseToLowerCaseUnderscored($this->extensionName);
		$this->setTsConfig($extension);
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
		$this->pageUid = (int)GeneralUtility::_GET('id');
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
	 * @return void
	 */
	protected function setTsConfig($extensionName) {
		$tsConfig = BackendUtility::getPagesTSconfig($this->pageUid);
		if (isset($tsConfig[$extensionName . '.' ]['module.']) && is_array($tsConfig[$extensionName . '.']['module.'])) {
			$this->tsConfiguration = $tsConfig[$extensionName . '.']['module.'];
		}
	}
}
