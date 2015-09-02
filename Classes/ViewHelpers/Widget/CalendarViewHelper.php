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
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $objects
	 * @param \Webfox\T3events\Domain\Model\Dto\CalendarConfiguration $configuration
	 * @return string
	 */
	public function render(QueryResultInterface $objects, CalendarConfiguration $configuration = NULL ) {
		return $this->initiateSubRequest();
	}
}
