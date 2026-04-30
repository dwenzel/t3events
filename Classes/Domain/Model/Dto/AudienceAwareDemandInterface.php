<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface AudienceAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface AudienceAwareDemandInterface
{
    public function getAudiences();

    public function setAudiences(?string $audiences);

    public function getAudienceField();
}
