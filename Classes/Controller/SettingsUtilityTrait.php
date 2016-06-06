<?php
namespace Webfox\T3events\Controller;

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
     * injects the settings utility
     *
     * @param SettingsUtility $settingsUtility
     */
    public function injectSettingsUtility(SettingsUtility $settingsUtility) {
        $this->settingsUtility = $settingsUtility;
    }

}
