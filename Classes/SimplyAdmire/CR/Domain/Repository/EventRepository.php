<?php
namespace SimplyAdmire\CR\Domain\Repository;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class EventRepository extends Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array('timestamp' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_ASCENDING);

}