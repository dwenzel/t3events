<?php
namespace Webfox\T3events\Controller;

use TYPO3\CMS\Extbase\Utility\ArrayUtility;
use Webfox\T3events\Utility\SettingsUtility;

/**
 * Class SettingsUtilityTrait

 *
*@package Controller
 */
trait SettingsUtilityTrait
{
    /**
     * @var \Webfox\T3events\Utility\SettingsUtility
     */
    protected $settingsUtility;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var string
     */
    protected $actionMethodName = 'indexAction';

    /**
     * injects the settings utility
     *
     * @param SettingsUtility $settingsUtility
     */
    public function injectSettingsUtility(SettingsUtility $settingsUtility) {
        $this->settingsUtility = $settingsUtility;
    }

    /**
     * Merges TypoScript settings for action an controller into one array
     * @return array
     */
    public function mergeSettings()
    {
        $actionName = preg_replace('/Action$/', '', $this->actionMethodName);
        $controllerKey = $this->settingsUtility->getControllerKey($this);
        $controllerSettings = [];
        $actionSettings = [];
        if (!empty($this->settings[$controllerKey])) {
            $controllerSettings = $this->settings[$controllerKey];
        }
        $allowedControllerSettingKeys = ['search', 'notify'];
        foreach ($controllerSettings as $key=>$value) {
            if (!in_array($key, $allowedControllerSettingKeys)) {
                unset($controllerSettings[$key]);
            }
        }
        if (!empty($this->settings[$controllerKey][$actionName])) {
            $actionSettings = $this->settings[$controllerKey][$actionName];
        }

        $typoScriptSettings = ArrayUtility::arrayMergeRecursiveOverrule($controllerSettings, $actionSettings, false, false);
        return ArrayUtility::arrayMergeRecursiveOverrule($this->settings, $typoScriptSettings, false, false);
    }


}
