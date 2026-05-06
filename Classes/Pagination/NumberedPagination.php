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
    protected int $maximumNumberOfLinks = 3;

    protected int $displayRangeStart = 0;

    protected int $displayRangeEnd = 0;

    protected bool $hasLessPages = false;

    protected bool $hasMorePages = false;

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

        $this->displayRangeStart = max($this->displayRangeStart, 1);
        $this->displayRangeEnd = min($this->displayRangeEnd, $numberOfPages);

        $this->hasLessPages = $this->displayRangeStart > 1;
        $this->hasMorePages = $this->displayRangeEnd + 1 < $this->paginator->getNumberOfPages();
    }

    public function __construct(protected PaginatorInterface $paginator, int $maximumNumberOfLinks = 0)
    {
        if (0 < $maximumNumberOfLinks) {
            $this->maximumNumberOfLinks = $maximumNumberOfLinks;
        }
        $this->calculateDisplayRange();
    }

    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }

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

    public function getNextPageNumber(): ?int
    {
        $nextPage = $this->paginator->getCurrentPageNumber() + 1;

        return $nextPage <= $this->paginator->getNumberOfPages()
            ? $nextPage
            : null;
    }

    public function getFirstPageNumber(): int
    {
        return 1;
    }

    public function getLastPageNumber(): int
    {
        return $this->paginator->getNumberOfPages();
    }

    public function getStartRecordNumber(): int
    {
        if ($this->paginator->getCurrentPageNumber() > $this->paginator->getNumberOfPages()) {
            return 0;
        }

        return $this->paginator->getKeyOfFirstPaginatedItem() + 1;
    }

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

    public function getHasLessPages(): bool
    {
        return $this->hasLessPages;
    }

    public function getHasMorePages(): bool
    {
        return $this->hasMorePages;
    }

    public function getMaximumNumberOfLinks(): int
    {
        return $this->maximumNumberOfLinks;
    }

    public function getDisplayRangeStart(): int
    {
        return $this->displayRangeStart;
    }

    public function getDisplayRangeEnd(): int
    {
        return $this->displayRangeEnd;
    }
}
