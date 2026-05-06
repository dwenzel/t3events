<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use DWenzel\T3events\Domain\Model\Dto\AudienceAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AudienceConstraintRepositoryTrait
 * Provides method for Audience constraint repositories
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait AudienceConstraintRepositoryTrait
{
    /**
     * Create Audience constraints from demand (time restriction)
     *
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createAudienceConstraints(QueryInterface $query, AudienceAwareDemandInterface $demand): array
    {
        $audienceConstraints = [];
        $audienceField = $demand->getAudienceField();
        if ($demand->getAudiences() !== null) {
            $audiences = GeneralUtility::intExplode(',', $demand->getAudiences(), true);
            foreach ($audiences as $audience) {
                $audienceConstraints[] = $query->contains($audienceField, $audience);
            }
        }

        return $audienceConstraints;
    }
}
