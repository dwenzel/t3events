<?php
namespace DWenzel\T3events\Domain\Model\Dto;

/**
 * This file is part of the TYPO3 CMS project.
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

/**
 * Search object for searching text in fields
 *
 * @package placements
 */
class Search extends AbstractEntity
	implements LocationAwareInterface {
	use LocationAwareTrait;

	/**
	 * Basic search word
	 *
	 * @var string
	 */
	protected $subject;

	/**
	 * Search fields
	 *
	 * @var string
	 */
	protected $fields;

	/**
	 * Get the subject
	 *
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Set subject
	 *
	 * @param string $subject
	 * @return void
	 */
	public function setSubject($subject) {
		$this->subject = $subject;
	}

	/**
	 * Get fields
	 *
	 * @return string A comma separated list of search fields
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * Set fields
	 *
	 * @param string $fields A comma separated list of search fields
	 * @return void
	 */
	public function setFields($fields) {
		$this->fields = $fields;
	}

}
