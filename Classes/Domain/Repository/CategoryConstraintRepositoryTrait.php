<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
use DWenzel\T3events\Domain\Model\Dto\CategoryAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for Category constraint repositories
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait CategoryConstraintRepositoryTrait
{
    /**
     * Create Category constraints from demand (time restriction)
     *
     * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
     * @throws InvalidQueryException
     */
    public function createCategoryConstraints(QueryInterface $query, CategoryAwareDemandInterface $demand): array
    {
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
