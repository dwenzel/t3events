<?php
namespace DWenzel\T3events\ViewHelpers\Event;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Repository\EventRepository;
use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Render a list of performances of a given event
 *
 * @deprecated
 */
class PerformancesViewHelper extends AbstractTagBasedViewHelper
{

    /**
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\DWenzel\T3events\Domain\Model\Performance>
     */
    protected $performances;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
     * eventRepository
     *
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * injectEventRepository
     *
     * @param EventRepository $eventRepository
     * @return void
     */
    public function injectEventRepository(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * Initialize Arguments
     */
    public function initializeArguments()
    {
        parent::registerArgument('event', Event::class, 'Event whose performances should be rendered.', true);
        parent::registerArgument('tagName', 'string', 'Tag name to use for enclosing container', false, 'div');
        parent::registerArgument('tagNameChildren', 'string', 'Tag name to use for child nodes', false, 'span');
        parent::registerArgument('type', 'string', 'Result type: available options are complete, list, dateRange, crucialStatus', true);
        parent::registerArgument('class', 'string', 'Class attribute for enclosing container', false, 'list');
        parent::registerArgument('classChildren', 'string', 'Class attribute for children', false, 'single');
        parent::registerArgument('classFirst', 'string', 'Class name for first child', false, 'first');
        parent::registerArgument('classLast', 'string', 'Class name for last child', false, 'last');
        parent::registerArgument('childSeparator', 'string', 'Character or string separating children entries', false, ', ');
        parent::registerArgument('dateFormat', 'string', 'A string describing the date format - see php date() for options', false, 'd.m.Y');
    }

    /**
     * Render method
     *
     * @return string
     */
    public function render()
    {
        $this->performances = $this->arguments['event']->getPerformances();
        $this->tagName = $this->arguments['tagName'];
        $this->tagNameChildren = $this->arguments['tagNameChildren'];
        $this->classChildren = $this->arguments['classChildren'];
        $this->class = $this->arguments['class'];
        $this->initialize();
        $type = $this->arguments['type'];
        $content = '';
        $title = '';
        switch ($type) {
            case 'dateRange':
                $content = $this->getDateRange();
                break;
            case 'crucialStatus':
                if ($status = $this->getCrucialStatus()) {
                    $title = $status['title'];
                    $this->class .= ' ' . $status['cssClass'];
                    if ($this->renderChildren() == null) {
                        $content = $status['title'];
                    }
                }
                break;
            case 'lowestPrice':
                //return raw number to allow using <f:format.currency />
                return $this->getLowestPrice();
                break;
            default:
                break;
        }
        $this->tag->setContent($content);
        $this->tag->addAttribute('class', $this->class);
        $this->tag->addAttribute('title', $title);
        $this->tag->forceClosingTag(true);
        $this->renderChildren();
        $content = $this->tag->render();
        $content .= $this->renderChildren();

        return $content;
    }

    /**
     * Get date range of performances
     *
     * @return array
     */
    public function getDateRange()
    {
        $format = $this->arguments['dateFormat'];
        if ($format === '') {
            $format = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] ?: 'Y-m-d';
        }

        $timestamps = array();
        $dateRange = '';
        /** @var Performance $performance */
        foreach ($this->performances as $performance) {
            $timestamps[] = $performance->getDate()->getTimestamp();
        }
        sort($timestamps);
        if (strpos($format, '%') !== false) {
            $dateRange = strftime($format, $timestamps[0]);
            $dateRange .= ' - ' . strftime($format, end($timestamps));
        } else {
            $dateRange = date($format, $timestamps[0]);
            $dateRange .= ' - ' . date($format, end($timestamps));
        }

        return $dateRange;
    }

    /**
     * Get crucial status over all performances. Returns the status with the highest priority.
     *
     * @return string
     */
    public function getCrucialStatus()
    {
        $states = array();
        foreach ($this->performances as $performance) {
            $status = $performance->getStatus();
            if ($status) {
                array_push($states,
                    array(
                        'title' => $status->getTitle(),
                        'priority' => $status->getPriority(),
                        'cssClass' => $status->getCssClass()
                    )
                );
            }
        }
        if (count($states)) {
            usort($states, function ($a, $b) {
                return $a['priority'] - $b['priority'];
            });

            return $states[0];
        } else {
            return '';
        }
    }

    /**
     * Get lowest price over all performances and ticket classes.
     *
     * @return float
     */
    private function getLowestPrice()
    {
        $prices = array();
        foreach ($this->performances as $performance) {
            $ticketClasses = $performance->getTicketClass();
            foreach ($ticketClasses as $ticketClass) {
                $prices[] = ($ticketClass->getPrice()) ? $ticketClass->getPrice() : 0;
            }
        }
        sort($prices);

        return (float)$prices[0];
    }

    /**
     * Injects the Configuration Manager and is initializing the framework settings
     *
     * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager An instance of the Configuration Manager
     * @return void
     */
    public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager)
    {
        $this->configurationManager = $configurationManager;

        $tsSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK,
            't3events',
            't3events_events'
        );
        $originalSettings = $this->configurationManager->getConfiguration(
            \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS
        );

        // start override
        if (isset($tsSettings['settings']['overrideFlexformSettingsIfEmpty'])) {
            $overrideIfEmpty = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $tsSettings['settings']['overrideFlexformSettingsIfEmpty'], true);
            foreach ($overrideIfEmpty as $key) {
                // if flexform setting is empty and value is available in TS
                if ((!isset($originalSettings[$key]) || empty($originalSettings[$key]))
                    && isset($tsSettings['settings'][$key])
                ) {
                    $originalSettings[$key] = $tsSettings['settings'][$key];
                }
            }
        }

        $this->settings = $originalSettings;
    }
}
