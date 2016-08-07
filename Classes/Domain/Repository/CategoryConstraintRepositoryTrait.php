<?php
namespace Webfox\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for Category constraint repositories
 *
 * @package Webfox\T3events\Domain\Repository
 */
trait CategoryConstraintRepositoryTrait {
	/**
	 * Create Category constraints from demand (time restriction)
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \Webfox\T3events\Domain\Model\Dto\CategoryAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createCategoryConstraints(QueryInterface $query, $demand) {
		$categoryConstraints = [];
		$categoryField = $demand->getCategoryField();
		if ($demand->getCategories() !== null) {
			$categories = GeneralUtility::intExplode(',', $demand->getCategories(), true);
			foreach ($categories as $category) {
				$categoryConstraints[] = $query->contains($categoryField, $category);
			}
		}

		return $categoryConstraints;
	}
}
