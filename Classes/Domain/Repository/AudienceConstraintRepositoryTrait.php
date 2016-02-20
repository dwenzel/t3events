<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AudienceConstraintRepositoryTrait
 * Provides method for Audience constraint repositories
 *
 * @package Webfox\T3events\Domain\Repository
 */
trait AudienceConstraintRepositoryTrait {
	/**
	 * Create Audience constraints from demand (time restriction)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\AudienceAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createAudienceConstraints(QueryInterface $query, $demand) {
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
