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
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;
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
    /**
     * @var mixed
     */
    public $tagNameChildren;
    /**
     * @var mixed
     */
    public $classChildren;
    public $class;
    use ConfigurationManagerTrait;

    /**
     * @var ObjectStorage<Performance>
     */
    protected $performances;
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
    #[\Override]
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
     *
     * @return string
     */
    #[\Override]
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
     *
     * @return array
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
            $timestamps[] = $performance->getDate()->getTimestamp();
        }
        sort($timestamps);
        if (str_contains((string) $format, '%')) {
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
            usort($states, fn($a, $b): int|float => $a['priority'] - $b['priority']);

            return $states[0];
        }
        return '';
    }
}
