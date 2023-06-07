<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 CMS extension "bmg_sitepackage".
 *
 * Copyright (C) 2022 Oliver Noth <o.noth@familie-redlich.de>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace DWenzel\T3events\Pagination;

use TYPO3\CMS\Core\Pagination\PaginationInterface;
use TYPO3\CMS\Core\Pagination\PaginatorInterface;

/**
 * NumberedPagination
 *
 * @author Oliver Noth <o.noth@familie-redlich.de>
 * @license GPL-2.0-or-later
 */
final class NumberedPagination implements PaginationInterface
{
    /**
     * @var PaginatorInterface
     */
    protected PaginatorInterface $paginator;

    /**
     * @var int
     */
    protected int $maximumNumberOfLinks = 3;

    /**
     * @var int
     */
    protected int $displayRangeStart = 0;

    /**
     * @var int
     */
    protected int $displayRangeEnd = 0;

    /**
     * @var bool
     */
    protected bool $hasLessPages = false;

    /**
     * @var bool
     */
    protected bool $hasMorePages = false;

    /**
     * @return void
     */
    protected function calculateDisplayRange(): void
    {
        $numberOfPages = $this->paginator->getNumberOfPages();
        $currentPage = $this->paginator->getCurrentPageNumber();

        $maximumNumberOfLinks = $this->maximumNumberOfLinks;
        if ($maximumNumberOfLinks > $numberOfPages) {
            $maximumNumberOfLinks = $numberOfPages;
        }

        $delta = (int) ($maximumNumberOfLinks / 2);
        $this->displayRangeStart = $currentPage - $delta;
        $this->displayRangeEnd = $currentPage + $delta - ($maximumNumberOfLinks % 2 === 0 ? 1 : 0);
        if ($this->displayRangeStart < 1) {
            $this->displayRangeEnd -= $this->displayRangeStart - 1;
        }
        if ($this->displayRangeEnd > $numberOfPages) {
            $this->displayRangeStart -= $this->displayRangeEnd - $numberOfPages;
        }

        $this->displayRangeStart = (int) max($this->displayRangeStart, 1);
        $this->displayRangeEnd = (int) min($this->displayRangeEnd, $numberOfPages);

        $this->hasLessPages = $this->displayRangeStart > 1;
        $this->hasMorePages = $this->displayRangeEnd + 1 < $this->paginator->getNumberOfPages();
    }

    /**
     * @param PaginatorInterface $paginator
     * @param int $maximumNumberOfLinks
     */
    public function __construct(PaginatorInterface $paginator, int $maximumNumberOfLinks = 0)
    {
        $this->paginator = $paginator;
        if (0 < $maximumNumberOfLinks) {
            $this->maximumNumberOfLinks = $maximumNumberOfLinks;
        }
        $this->calculateDisplayRange();
    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

    /**
     * @return int|null
     */
    public function getPreviousPageNumber(): ?int
    {
        $previousPage = $this->paginator->getCurrentPageNumber() - 1;

        if ($previousPage > $this->paginator->getNumberOfPages()) {
            return null;
        }

        return $previousPage >= $this->getFirstPageNumber()
            ? $previousPage
            : null;
    }

    /**
     * @return int|null
     */
    public function getNextPageNumber(): ?int
    {
        $nextPage = $this->paginator->getCurrentPageNumber() + 1;

        return $nextPage <= $this->paginator->getNumberOfPages()
            ? $nextPage
            : null;
    }

    /**
     * @return int
     */
    public function getFirstPageNumber(): int
    {
        return 1;
    }

    /**
     * @return int
     */
    public function getLastPageNumber(): int
    {
        return $this->paginator->getNumberOfPages();
    }

    /**
     * @return int
     */
    public function getStartRecordNumber(): int
    {
        if ($this->paginator->getCurrentPageNumber() > $this->paginator->getNumberOfPages()) {
            return 0;
        }

        return $this->paginator->getKeyOfFirstPaginatedItem() + 1;
    }

    /**
     * @return int
     */
    public function getEndRecordNumber(): int
    {
        if ($this->paginator->getCurrentPageNumber() > $this->paginator->getNumberOfPages()) {
            return 0;
        }

        return $this->paginator->getKeyOfLastPaginatedItem() + 1;
    }

    /**
     * @return int[]
     */
    public function getAllPageNumbers(): array
    {
        return range($this->displayRangeStart, $this->displayRangeEnd);
    }

    /**
     * @return bool
     */
    public function getHasLessPages(): bool
    {
        return $this->hasLessPages;
    }

    /**
     * @return bool
     */
    public function getHasMorePages(): bool
    {
        return $this->hasMorePages;
    }

    /**
     * @return int
     */
    public function getMaximumNumberOfLinks(): int
    {
        return $this->maximumNumberOfLinks;
    }

    /**
     * @return int
     */
    public function getDisplayRangeStart(): int
    {
        return $this->displayRangeStart;
    }

    /**
     * @return int
     */
    public function getDisplayRangeEnd(): int
    {
        return $this->displayRangeEnd;
    }
}
