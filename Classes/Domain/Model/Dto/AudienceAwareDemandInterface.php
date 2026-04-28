<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface AudienceAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface AudienceAwareDemandInterface
{
    /**
     * @return string|null
     */
    public function getAudiences(): ?string;

    /**
     * @param string|null $audiences
     */
    public function setAudiences(?string $audiences): void;

    /**
     * @return string
     */
    public function getAudienceField(): string;
}
