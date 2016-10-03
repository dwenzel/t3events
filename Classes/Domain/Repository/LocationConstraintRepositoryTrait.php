<?php
namespace DWenzel\T3events\Domain\Repository;

use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Class LocationConstraintRepositoryTrait
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait LocationConstraintRepositoryTrait {
	/**
	 * @var \DWenzel\T3events\Utility\GeoCoder
	 * @inject
	 */
	protected $geoCoder;

	/**
	 * Create location constraints from demand
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
	 * @param \DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface $demand
	 * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
	 */
	public function createLocationConstraints(QueryInterface $query, $demand) {
		$locationConstraints = [];

		if ($demand->getSearch()) {
			$locationConstraints = [];
			$search = $demand->getSearch();

			// search by bounding box
			$bounds = $search->getBounds();
			$location = $search->getLocation();
			$radius = $search->getRadius();

			if (!empty($location)
				AND !empty($radius)
				AND empty($bounds)
			) {
				$geoLocation = $this->geoCoder->getLocation($location);
				$bounds = $this->geoCoder->getBoundsByRadius($geoLocation['lat'], $geoLocation['lng'], $radius / 1000);
			}
			if ($bounds AND
				!empty($bounds['N']) AND
				!empty($bounds['S']) AND
				!empty($bounds['W']) AND
				!empty($bounds['E'])
			) {
				$locationConstraints[] = $query->greaterThan('latitude', $bounds['S']['lat']);
				$locationConstraints[] = $query->lessThan('latitude', $bounds['N']['lat']);
				$locationConstraints[] = $query->greaterThan('longitude', $bounds['W']['lng']);
				$locationConstraints[] = $query->lessThan('longitude', $bounds['E']['lng']);
			}
		}

		return $locationConstraints;
	}
}