<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface EventTypeAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface EventTypeAwareDemandInterface
{
    /**
     * @return string|null
     */
    public function getEventTypes(): ?string;

    /**
     * @param string|null $eventTypes
     */
    public function setEventTypes(?string $eventTypes): void;

    /**
     * @return string
     */
    public function getEventTypeField(): string;
}
