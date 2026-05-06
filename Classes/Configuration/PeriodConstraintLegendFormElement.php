<?php

declare(strict_types=1);

namespace DWenzel\T3events\Configuration;

use TYPO3\CMS\Backend\Form\Element\AbstractFormElement;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class PeriodConstraintLegendFormElement
 * @package DWenzel\T3events\Configuration
 */
class PeriodConstraintLegendFormElement extends AbstractFormElement
{
    /** @return array<string, mixed> */
    public function render(): array
    {
        $fieldInformationResult = $this->renderFieldInformation();
        $resultArray = $this->mergeChildReturnIntoExistingResult($this->initializeResultArray(), $fieldInformationResult, false);

        $periodConstraintLegend = GeneralUtility::makeInstance(PeriodConstraintLegend::class);

        $resultArray['html'] = $periodConstraintLegend->render(['row' => $this->data['databaseRow']]);

        return $resultArray;
    }
}
