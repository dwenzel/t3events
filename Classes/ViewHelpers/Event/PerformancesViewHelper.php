<?php
namespace DWenzel\T3events\ViewHelpers\Event;

use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use DWenzel\T3events\Configuration\ConfigurationManagerTrait;
use DWenzel\T3events\Domain\Model\Event;
use DWenzel\T3events\Domain\Model\Performance;
use DWenzel\T3events\Domain\Repository\EventRepository;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper;

/**
 * Render a list of performances of a given event
 *
 * @deprecated
 */
class PerformancesViewHelper extends AbstractTagBasedViewHelper
{
    public mixed $tagNameChildren = null;
    public mixed $classChildren = null;
    public mixed $class = null;
    use ConfigurationManagerTrait;

    /**
     * @var ObjectStorage<Performance>
     */
    protected ObjectStorage $performances;
    /**
     * Constructor
     */
    public function __construct(protected EventRepository $eventRepository)
    {
        parent::__construct();
    }

    /**
     * Initialize Arguments
     */
    public function initializeArguments(): void
    {
        parent::registerArgument('event', Event::class, 'Event whose performances should be rendered.', true);
        parent::registerArgument('tagName', 'string', 'Tag name to use for enclosing container', false, 'div');
        parent::registerArgument('tagNameChildren', 'string', 'Tag name to use for child nodes', false, 'span');
        parent::registerArgument('type', 'string', 'Result type: available options are: dateRange, crucialStatus', true);
        parent::registerArgument('class', 'string', 'Class attribute for enclosing container', false, 'list');
        parent::registerArgument('classChildren', 'string', 'Class attribute for children', false, 'single');
        parent::registerArgument('classFirst', 'string', 'Class name for first child', false, 'first');
        parent::registerArgument('classLast', 'string', 'Class name for last child', false, 'last');
        parent::registerArgument('childSeparator', 'string', 'Character or string separating children entries', false, ', ');
        parent::registerArgument('dateFormat', 'string', 'A string describing the date format - see php date() for options', false, 'd.m.Y');
    }

    /**
     * Render method
     */
    public function render(): string
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
                $status = $this->getCrucialStatus();
                if (is_array($status)) {
                    $title = $status['title'];
                    $this->class .= ' ' . $status['cssClass'];
                    if ($this->renderChildren() === null) {
                        $content = $status['title'];
                    }
                }
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

        return $content . $this->renderChildren();
    }

    /**
     * Get date range of performances
     */
    public function getDateRange(): string
    {
        $format = $this->arguments['dateFormat'];
        if ($format === '') {
            $format = $GLOBALS['TYPO3_CONF_VARS']['SYS']['ddmmyy'] ?: 'Y-m-d';
        }

        $timestamps = [];
        $dateRange = '';
        /** @var Performance $performance */
        foreach ($this->performances as $performance) {
            $date = $performance->getDate();
            if ($date !== null) {
                $timestamps[] = $date->getTimestamp();
            }
        }
        sort($timestamps);
        $lastTimestamp = end($timestamps);
        $firstTimestamp = $timestamps[0] ?? 0;
        $resolvedLastTimestamp = $lastTimestamp !== false ? $lastTimestamp : $firstTimestamp;
        if (str_contains((string) $format, '%')) {
            $dateRange = strftime($format, $firstTimestamp);
            $dateRange .= ' - ' . strftime($format, $resolvedLastTimestamp);
        } else {
            $dateRange = date($format, $firstTimestamp);
            $dateRange .= ' - ' . date($format, $resolvedLastTimestamp);
        }

        return $dateRange;
    }

    /**
     * Get crucial status over all performances. Returns the status with the highest priority.
     *
     * @return array{title: string, cssClass: string, priority: int}|string
     */
    public function getCrucialStatus(): array|string
    {
        $states = [];
        foreach ($this->performances as $performance) {
            $status = $performance->getStatus();
            if ($status) {
                $states[] = ['title' => $status->getTitle(), 'priority' => $status->getPriority(), 'cssClass' => $status->getCssClass()];
            }
        }
        if ($states !== []) {
            usort($states, fn($a, $b): int => $a['priority'] - $b['priority']);

            return $states[0];
        }
        return '';
    }
}
