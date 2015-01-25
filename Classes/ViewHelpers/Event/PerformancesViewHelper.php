<?php
namespace Webfox\T3events\ViewHelpers\Event;
/***************************************************************
*  Copyright notice
*
*  (c)* 2012 Dirk Wenzel <wenzel@webfox01.de> All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * 
 * Render a list of performances of a given event
 * @author Dirk Wenzel
 * @package T3events
 * @subpackage ViewHelpers/Event
 */

class PerformancesViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {
    /*
    * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage
    */
    protected  $performances;

    /**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     */
    protected $configurationManager;

    /**
	 * eventRepository
	 *
	 * @var \Webfox\T3events\Domain\Repository\EventRepository
	 */
	protected $eventRepository;

	/**
	 * injectEventRepository
	 *
	 * @param \Webfox\T3events\Domain\Repository\EventRepository $eventRepository
	 * @return void
	 */
	public function injectEventRepository(\Webfox\T3events\Domain\Repository\EventRepository $eventRepository) {
		$this->eventRepository = $eventRepository;
	}
	
	/**
	 * 
	 * Inititalize Arguments
	 */
	
	public function initializeArguments() {
		parent::registerArgument('event', '\\Webfox\\T3events\\Domain\\Model\\Event', 'Event whose performances should be rendered.', TRUE);
       	parent::registerArgument('tagName', 'string', 'Tag name to use for enclosing container', FALSE, 'div');
        parent::registerArgument('tagNameChildren', 'string', 'Tag name to use for child nodes', FALSE, 'span');
        parent::registerArgument('type', 'string', 'Result type: available options are complete, uniqueLocationsList, list, dateRange, crucialStatus', TRUE);
        parent::registerArgument('class', 'string', 'Class attribute for enclosing container', FALSE, 'list');
        parent::registerArgument('classChildren', 'string', 'Class attribute for children', FALSE, 'single');
        parent::registerArgument('classFirst', 'string', 'Class name for first child', FALSE, 'first');
        parent::registerArgument('classLast', 'string', 'Class name for last child', FALSE, 'last');
        parent::registerArgument('childSeparator', 'string', 'Character or string separating children entries', FALSE, ', ');
        parent::registerArgument('dateFormat', 'string', 'A string describing the date format - see php date() for options', FALSE, 'd.m.Y');
    }
	
    /**
     * 
     * Render method
     * @return \string
     */
	public function render(){
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
			case 'uniqueLocationsList':
				$content= $this->renderChildrenList($this->getLocationsArr());
				break;
			case 'dateRange':
				$content=$this->getDateRange();
				break;
			case 'crucialStatus':
				if($status = $this->getCrucialStatus()) {
					$title = $status['title'];
					$this->class .= ' ' . $status['cssClass'];
					if($this->renderChildren() == NULL) {
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
		$this->tag->forceClosingTag(TRUE);
		$this->renderChildren();
		$content = $this->tag->render();
		$content .= $this->renderChildren();
        return $content;
    }
    
    /**
     * Get array of locations for event
     * @param boolean unique Remove duplicate entries
     * @return array
     */
    public function getLocationsArr($unique = TRUE){
    	$contentArr = array();
    	
    	// get place and name of performances
    	foreach ($this->performances as $performance){
    	    $eventLocation = $performance->getEventLocation();
    	    if ($eventLocation) {
    	            $val = ($eventLocation->getPlace())?$eventLocation->getPlace(): '';
                $val .= ($eventLocation->getName())? ' '. $eventLocation->getName(): '';
                array_push($contentArr, $val);
    	    }
    	}
    	// make array unique
    	$contentArr = ($unique)?array_values(array_unique($contentArr)): $contentArr;
    	
    	// add separator
    	$contentArrCount = count($contentArr);
    	for($i=0; $i<$contentArrCount-1; $i++){
    		$contentArr[$i] = $contentArr[$i] . $this->arguments['childSeparator'];
    	}
    	
    	return $contentArr;
    }
    
    /**
    * Render location list children
    * @param \array $children
    * @return \string
    */
    public function renderChildrenList($children) {
    	$content = '';
    	$tagBuilder = new \TYPO3\CMS\Fluid\Core\ViewHelper\TagBuilder;
    	$tagBuilder->setTagName($this->arguments['tagNameChildren']);
    	$tagBuilder->forceClosingTag(TRUE);
    	$itemCount = count($children);
    	for ($i=0; $i<$itemCount; $i++){
    		$class = $this->arguments['classChildren'];
    		if ($i==0){
    			$class .= ' ' .$this->arguments['classFirst']; 
    		}
    		if($i== ($itemCount-1)){
    			$class .= ' '. $this->arguments['classLast'];
    		}
    		$tagBuilder->setContent($children[$i]);
    		$tagBuilder->addAttribute('class', $class);
    		$content .= $tagBuilder->render();
    	}
    	$tagBuilder->reset();
    	return $content;
    }
    
    /** 
    * Get date range of performances
    * @return \array 
    */
    public function getDateRange(){
        $format = $this->arguments['dateFormat'];
    	$dateArr = array();
	    $dateRange = '';
    	foreach ($this->performances as $performance ) {
			array_push($dateArr, $performance->getDate()->getTimestamp());
		}
		sort($dateArr);
		$dateRange = date( $format, $dateArr[0]);
		$dateRange .= ' - '. date($format, end($dateArr));
		return $dateRange;
    }
    
    /**
    * Get crucial status over all performances. Returns the status with the highest priority.
    * @return \string
    */
    public function getCrucialStatus() {
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
            usort($states, function($a, $b) {
                return $a['priority'] - $b['priority'];
            });
            return $states[0];            
        }
        else {
            return '';
        }
    }
    
    /**
     * Get lowest price over all performances and ticket classes.
     * @return float
     */
	private function getLowestPrice() {
		$prices = array();
		foreach ($this->performances as $performance) {
			$ticketClasses = $performance->getTicketClass();
			foreach ($ticketClasses as $ticketClass){
				$prices[] = ($ticketClass->getPrice())?$ticketClass->getPrice():0;
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
    public function injectConfigurationManager(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager) {
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
            $overrideIfEmpty = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $tsSettings['settings']['overrideFlexformSettingsIfEmpty'], TRUE);
            foreach ($overrideIfEmpty as $key) {
                    // if flexform setting is empty and value is available in TS
                if ((!isset($originalSettings[$key]) || empty($originalSettings[$key]))
                        && isset($tsSettings['settings'][$key])) {
                    $originalSettings[$key] = $tsSettings['settings'][$key];
                }
            }
        }
        
        $this->settings = $originalSettings;
    }
}

