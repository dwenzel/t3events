<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use DWenzel\T3events\Domain\Model\Dto\GenreAwareDemandInterface;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class StatusConstraintRepositoryTrait
 * Provides method for Genre constraint repositories
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait GenreConstraintRepositoryTrait
{
    /**
     * Create Genre constraints from demand (time restriction)
     *
     * @param QueryInterface<DomainObjectInterface> $query
     * @return array<ConstraintInterface>
     */
    public function createGenreConstraints(QueryInterface $query, GenreAwareDemandInterface $demand)
    {
        $genreConstraints = [];
        $genreField = $demand->getGenreField();
        if ($demand->getGenres() !== null) {
            $genres = GeneralUtility::intExplode(',', $demand->getGenres(), true);
            foreach ($genres as $genre) {
                $genreConstraints[] = $query->contains($genreField, $genre);
            }
        }

        return $genreConstraints;
    }
}
