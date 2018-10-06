<?php
namespace DWenzel\T3events\Domain\Repository;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */

use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use DWenzel\T3events\UnsupportedMethodException;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/**
 * Class DemandedRepositoryTrait
 *
 * @package DWenzel\T3events\Domain\Repository
 */
trait DemandedRepositoryTrait
{

    /**
     * Returns an array of constraints created from a given demand object.
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
     * @abstract
     */
    abstract public function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand);

    /**
     * Returns a query for objects of this repository
     *
     * @return \TYPO3\CMS\Extbase\Persistence\QueryInterface
     */
    abstract public function createQuery();

    /**
     * @var string $recordList A comma separated string containing uids
     * @var string $sortField Sort by field
     * @var string $sortOrder
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface Matching Records
     */
    public function findMultipleByUid($recordList, $sortField = 'uid', $sortOrder = QueryInterface::ORDER_ASCENDING)
    {
        $query = $this->createQuery();
        $uids = GeneralUtility::intExplode(',', $recordList, true);
        if ((bool)$uids) {
            $query->matching($query->in('uid', $uids));
        }
        $query->setOrderings([$sortField => $sortOrder]);

        return $query->execute();
    }

    /**
     * Returns the objects of this repository matching the demand.
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
     * @param boolean $respectEnableFields
     * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface|array
     */
    public function findDemanded(DemandInterface $demand, $respectEnableFields = true)
    {
        $query = $this->generateQuery($demand, $respectEnableFields);
        return $query->execute();
    }

    /**
     * Returns an array of orderings created from a given demand object.
     *
     * @param \DWenzel\T3events\Domain\Model\Dto\DemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint>
     */
    public function createOrderingsFromDemand(DemandInterface $demand)
    {
        $orderings = [];

        if ($demand->getOrder()) {
            $orderList = GeneralUtility::trimExplode(',', $demand->getOrder(), true);

            if (!empty($orderList)) {
                // go through every order statement
                foreach ($orderList as $orderItem) {
                    list($orderField, $ascDesc) = GeneralUtility::trimExplode('|', $orderItem, true);
                    // count == 1 means that no direction is given
                    if ($ascDesc) {
                        $orderings[$orderField] = ((strtolower($ascDesc) == 'desc') ?
                            QueryInterface::ORDER_DESCENDING :
                            QueryInterface::ORDER_ASCENDING);
                    } else {
                        $orderings[$orderField] = QueryInterface::ORDER_ASCENDING;
                    }
                }
            }
        }

        return $orderings;
    }


    /**
     * @param DemandInterface $demand
     * @param bool $respectEnableFields
     * @return QueryInterface
     */
    public function generateQuery(DemandInterface $demand, $respectEnableFields = true)
    {
        $query = $this->createQuery();
        $constraints = $this->createConstraintsFromDemand($query, $demand);

        if ($respectEnableFields === false) {
            $query->getQuerySettings()->setIgnoreEnableFields(true);
        }
        // Call hook functions for additional constraints
        if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'])) {
            $params = [
                'demand' => &$demand,
                'respectEnableFields' => &$respectEnableFields,
                'query' => &$query,
                'constraints' => &$constraints,
            ];
            foreach ($GLOBALS['TYPO3_CONF_VARS']['EXT']['t3events']['Domain/Repository/AbstractDemandedRepository.php']['findDemanded'] as $reference) {
                GeneralUtility::callUserFunction($reference, $params, $this);
            }
        }

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }

        if ($orderings = $this->createOrderingsFromDemand($demand)) {
            $query->setOrderings($orderings);
        }

        if ($demand->getLimit() !== null) {
            $query->setLimit((int)$demand->getLimit());
        }

        if ($demand->getOffset() !== null) {
            $query->setOffset((int)$demand->getOffset());
        }

        if ($demand->getStoragePages()) {
            $pageIds = GeneralUtility::intExplode(',', $demand->getStoragePages());
            $query->getQuerySettings()->setStoragePageIds($pageIds);
        }

        return $query;
    }


    /**
     * Combine constraints
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $constraints
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\Constraint> $additionalConstraints
     * @param string $conjunction
     */
    public function combineConstraints(QueryInterface $query, &$constraints, $additionalConstraints, $conjunction = null)
    {
        if (count($additionalConstraints)) {
            switch (strtolower($conjunction)) {
                case 'or':
                    $constraints[] = $query->logicalOr($additionalConstraints);
                    break;
                case 'notand':
                    foreach ($additionalConstraints as $additionalConstraint) {
                        $constraints[] = $query->logicalNot($query->logicalAnd($additionalConstraint));
                    }
                    break;
                case 'notor':
                    foreach ($additionalConstraints as $additionalConstraint) {
                        $constraints[] = $query->logicalNot($query->logicalOr($additionalConstraint));
                    }
                    break;
                default:
                    $constraints[] = $query->logicalAnd($additionalConstraints);
            }
        }
    }

    /**
     * Create search constraints from demand
     *
     * @param \TYPO3\CMS\Extbase\Persistence\QueryInterface $query
     * @param \DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface $demand
     * @return array<\TYPO3\CMS\Extbase\Persistence\QOM\Constraint>
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function createSearchConstraints(QueryInterface $query, SearchAwareDemandInterface $demand)
    {
        $searchConstraints = [];
        if ($search = $demand->getSearch()) {
            $subject = $search->getSubject();

            if (!empty($subject)) {
                // search text in specified search fields
                $searchFields = GeneralUtility::trimExplode(',', $search->getFields(), true);
                if (count($searchFields) === 0) {
                    throw new \UnexpectedValueException('No search fields given', 1382608407);
                }
                foreach ($searchFields as $field) {
                    $searchConstraints[] = $query->like($field, '%' . $subject . '%');
                }
            }
        }

        return $searchConstraints;
    }

    /**
     * Dispatches magic methods
     * We have to overwrite the parent method in order
     * to implement our own magic
     *
     * @param string $methodName The name of the magic method
     * @param string $arguments The arguments of the magic method
     * @return mixed
     * @throws UnsupportedMethodException
     */
    public function __call($methodName, $arguments)
    {
        $substring = substr($methodName, 0, 15);
        if ($substring === 'countContaining' && strlen($methodName) > 16) {
            $propertyName = lcfirst(substr($methodName, 15));
            $query = $this->createQuery();
            $result = $query->matching($query->contains($propertyName, $arguments[0]))->execute()->count();

            return $result;
        } elseif (
            count(class_parents($this))
            && is_callable('parent::__call')
        ) {
            return parent::__call($methodName, $arguments);
        }

        throw new UnsupportedMethodException(
            'The method "' . $methodName . '" is not supported by class' . __CLASS__ . 'using trait ' . __TRAIT__,
            1479289568
        );
    }
}
