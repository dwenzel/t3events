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
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use DWenzel\T3events\Domain\Model\Dto\DemandInterface;
use DWenzel\T3events\Domain\Model\Dto\SearchAwareDemandInterface;
use DWenzel\T3events\Events\QueryGeneratePreMatchEvent;
use DWenzel\T3events\UnsupportedMethodException;
use Psr\EventDispatcher\EventDispatcherInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException;
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
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     */
    abstract public function createConstraintsFromDemand(QueryInterface $query, DemandInterface $demand);

    /**
     * Returns a query for objects of this repository
     *
     * @return QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface>
     */
    abstract public function createQuery();

    /**
     * @param string $recordList A comma separated string containing uids
     * @param string $sortField Sort by field
     * @param string $sortOrder
     * @return QueryResultInterface<object> Matching Records
     */
    public function findMultipleByUid(string $recordList, string $sortField = 'uid', string $sortOrder = QueryInterface::ORDER_ASCENDING)
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
     * @param bool $respectEnableFields
     * @return QueryResultInterface<object>
     */
    public function findDemanded(DemandInterface $demand, bool $respectEnableFields = true)
    {
        $query = $this->generateQuery($demand, $respectEnableFields);
        return $query->execute();
    }

    /**
     * Returns an array of orderings created from a given demand object.
     *
     * @return array<string, string>
     */
    public function createOrderingsFromDemand(DemandInterface $demand)
    {
        $orderings = [];

        if ($demand->getOrder()) {
            $orderList = GeneralUtility::trimExplode(',', $demand->getOrder(), true);

            // go through every order statement
            foreach ($orderList as $orderItem) {
                [$orderField, $ascDesc] = GeneralUtility::trimExplode('|', $orderItem, true);
                // count == 1 means that no direction is given
                if ($ascDesc) {
                    $orderings[$orderField] = ((strtolower($ascDesc) === 'desc') ?
                        QueryInterface::ORDER_DESCENDING :
                        QueryInterface::ORDER_ASCENDING);
                } else {
                    $orderings[$orderField] = QueryInterface::ORDER_ASCENDING;
                }
            }
        }

        return $orderings;
    }


    /**
     * @return QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface>
     * @throws InvalidQueryException
     */
    public function generateQuery(?DemandInterface $demand = null, bool $respectEnableFields = true)
    {
        /** @var QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query */
        $query = $this->createQuery();
        if ($demand === null) {
            return $query;
        }
        $constraints = $this->createConstraintsFromDemand($query, $demand);

        if ($respectEnableFields === false) {
            $query->getQuerySettings()->setIgnoreEnableFields(true);
        }

        /** @var QueryGeneratePreMatchEvent $event */
        $event = GeneralUtility::makeInstance(EventDispatcherInterface::class)->dispatch(new QueryGeneratePreMatchEvent(
            $query,
            $demand,
            $constraints,
            $respectEnableFields,
            $this
        ));
        $query = $event->getQuery();
        $constraints = $event->getConstrains();
        $demand = $event->getDemand();

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd(...$constraints)
            );
        }

        if ($demand !== null && $orderings = $this->createOrderingsFromDemand($demand)) {
            $query->setOrderings($orderings);
        }

        if ($demand !== null && $demand->getLimit() !== null) {
            $query->setLimit((int)$demand->getLimit());
        }

        if ($demand !== null && $demand->getOffset() !== null) {
            $query->setOffset((int)$demand->getOffset());
        }

        if ($demand !== null && $demand->getStoragePages()) {
            $pageIds = GeneralUtility::intExplode(',', $demand->getStoragePages());
            $query->getQuerySettings()->setStoragePageIds($pageIds);
        }

        return $query;
    }


    /**
     * Combine constraints
     *
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface> $constraints
     * @param array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface> $additionalConstraints
     * @param string|null $conjunction
     */
    public function combineConstraints(QueryInterface $query, array &$constraints, array $additionalConstraints, ?string $conjunction = null)
    {
        if ($conjunction !== null && count($additionalConstraints)) {
            switch (strtolower($conjunction)) {
                case 'or':
                    $constraints[] = $query->logicalOr(...$additionalConstraints);
                    break;
                case 'notand':
                case 'notor':
                    foreach ($additionalConstraints as $additionalConstraint) {
                        $constraints[] = $query->logicalNot($additionalConstraint);
                    }
                    break;
                default:
                    $constraints[] = $query->logicalAnd(...$additionalConstraints);
            }
        } else {
            $constraints[] = $query->logicalAnd(...$additionalConstraints);
        }
    }

    /**
     * Create search constraints from demand
     *
     * @param QueryInterface<\TYPO3\CMS\Extbase\DomainObject\DomainObjectInterface> $query
     * @return array<\TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface>
     * @throws InvalidQueryException
     */
    public function createSearchConstraints(QueryInterface $query, SearchAwareDemandInterface $demand)
    {
        $searchConstraints = [];
        if ($search = $demand->getSearch()) {
            $subject = $search->getSubject();

            if (!empty($subject)) {
                // search text in specified search fields
                $searchFields = GeneralUtility::trimExplode(',', $search->getFields(), true);
                if ($searchFields === []) {
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
     * @param array<mixed> $arguments The arguments of the magic method
     * @return mixed
     * @throws UnsupportedMethodException
     */
    public function __call($methodName, $arguments)
    {
        $substring = substr($methodName, 0, 15);
        if ($substring === 'countContaining' && strlen($methodName) > 16) {
            $propertyName = lcfirst(substr($methodName, 15));
            $query = $this->createQuery();
            return $query->matching($query->contains($propertyName, $arguments[0]))->execute()->count();
        }
        $parents = class_parents($this);
        if ($parents !== false && count($parents)
        && is_callable('parent::__call')
        && $methodName !== '') {
            return parent::__call($methodName, $arguments);
        }

        throw new UnsupportedMethodException(
            'The method "' . $methodName . '" is not supported by class' . self::class . 'using trait ' . __TRAIT__,
            1479289568
        );
    }
}
