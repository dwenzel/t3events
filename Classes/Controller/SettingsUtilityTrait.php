<?php
namespace DWenzel\T3events\Controller;

use DWenzel\T3events\Utility\SettingsUtility;
use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class SettingsUtilityTrait
 *
 * @package Controller
 */
trait SettingsUtilityTrait
{
    protected ?SettingsUtility $settingsUtility = null;

    public function injectSettingsUtility(SettingsUtility $settingsUtility): void
    {
        $this->settingsUtility = $settingsUtility;
    }

    /**
     * Merges TypoScript settings for action an controller into one array
     * @return array<string, mixed>
     */
    public function mergeSettings(): array
    {
        $actionName = (string) preg_replace('/Action$/', '', $this->actionMethodName);
        $settingsUtility = $this->settingsUtility ?? GeneralUtility::makeInstance(SettingsUtility::class);
        $controllerKey = $settingsUtility->getControllerKey($this);
        $controllerSettings = [];
        $actionSettings = [];
        if (!empty($this->settings[$controllerKey])) {
            $controllerSettings = $this->settings[$controllerKey];
        }
        $allowedControllerSettingKeys = ['search', 'notify'];
        foreach (array_keys($controllerSettings) as $key) {
            if (!in_array($key, $allowedControllerSettingKeys)) {
                unset($controllerSettings[$key]);
            }
        }
        if (!empty($this->settings[$controllerKey][$actionName])) {
            $actionSettings = $this->settings[$controllerKey][$actionName];
        }

        ArrayUtility::mergeRecursiveWithOverrule($controllerSettings, $actionSettings);
        ArrayUtility::mergeRecursiveWithOverrule($controllerSettings, $this->settings);
        return $controllerSettings;
    }
}
