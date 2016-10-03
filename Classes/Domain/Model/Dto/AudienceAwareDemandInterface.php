<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * Interface AudienceAwareDemandInterface
 *
 * @package DWenzel\T3events\Domain\Model\Dto
 */
interface AudienceAwareDemandInterface {
	/**
	 * @return string
	 */
	public function getAudiences();

	/**
	 * @param string $audiences
	 * @return void
	 */
	public function setAudiences($audiences);

	/**
	 * @return string
	 */
	public function getAudienceField();
}
