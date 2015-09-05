<?php
namespace Webfox\T3events\ViewHelpers\Widget;

use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;
use Webfox\T3events\Domain\Model\Dto\CalendarConfiguration;

/**
 * This ViewHelper renders a Calendar.
 *
 * = Examples =
 *
 * <code title="required arguments">
 * <ts:widget.calendar objects="{events}" configuration="{calendarConfiguration}" />
 * </code>
 */
class CalendarViewHelper extends AbstractWidgetViewHelper {

	/**
	 * @var \Webfox\T3events\ViewHelpers\Widget\Controller\CalendarController
	 * @inject
	 */
	protected $controller;

	/**
	 * @var bool
	 */
	protected $ajaxWidget = FALSE;

	/**
	 * Initialize the arguments of the ViewHelper, and call the render() method of the ViewHelper.
	 *
	 * @return string the rendered ViewHelper.
	 */
	public function initialize() {
		if ($this->hasArgument('configuration') AND
			$this->arguments['configuration'] instanceof CalendarConfiguration) {
			/** @var CalendarConfiguration $configuration */
			$configuration = $this->arguments['configuration'];
			$this->ajaxWidget = $configuration->getAjaxEnabled();
		}
	}

	/**
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects
	 * @param \Webfox\T3events\Domain\Model\Dto\CalendarConfiguration $configuration
	 * @param string $id
	 * @return string
	 */
	public function render(QueryResultInterface $objects, CalendarConfiguration $configuration = NULL, $id = NULL) {
		return $this->initiateSubRequest();
	}
}
