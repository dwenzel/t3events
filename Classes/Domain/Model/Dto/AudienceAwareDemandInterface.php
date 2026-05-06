<?php

declare(strict_types=1);

namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface AudienceAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface AudienceAwareDemandInterface
{
    public function getAudiences(): ?string;

    public function setAudiences(?string $audiences): void;

    public function getAudienceField(): string;
}
