<?php


namespace DWenzel\T3events\Configuration;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Backend\Form\NodeFactory;
use TYPO3\CMS\Core\Imaging\IconFactory;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PeriodConstraintLegendFormElement
 * @package DWenzel\T3events\Configuration
 */
class PeriodConstraintLegendFormElement extends AbstractFormElement
{
    /**
     * Container objects give $nodeFactory down to other containers.
     *
     * @param NodeFactory $nodeFactory
     * @param array $data
     */
    public function __construct(NodeFactory $nodeFactory, array $data)
    {
        parent::__construct($nodeFactory, $data);
        $this->iconFactory = GeneralUtility::makeInstance(IconFactory::class);
    }

    public function render(): array
    {
        $fieldInformationResult = $this->renderFieldInformation();
        $resultArray = $this->mergeChildReturnIntoExistingResult($this->initializeResultArray(), $fieldInformationResult, false);

        $periodConstraintLegend = GeneralUtility::makeInstance(PeriodConstraintLegend::class);

        $resultArray['html'] = $periodConstraintLegend->render(['row' => $this->data['databaseRow']]);

        return $resultArray;
    }
}