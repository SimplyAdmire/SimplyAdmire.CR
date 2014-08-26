<?php
namespace SimplyAdmire\CR\Domain\Model;

use SimplyAdmire\CR\Domain\Events\AbstractEvent;
use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 * @ORM\Table(
 *	indexes={
 * 		@ORM\Index(name="nodeidentifier_workspace_dimensionshash", columns={"nodeidentifier", "workspace", "dimensionshash"})
 * 	}
 * )
 */
class Event {

	/**
	 * @var string
	 * @ORM\Column(type="text")
	 */
	protected $eventObject;

	/**
	 * @var string
	 */
	protected $nodeIdentifier;

	/**
	 * @var string
	 */
	protected $eventType;

	/**
	 * @var double
	 */
	protected $timestamp;

	/**
	 * @var string
	 */
	protected $workspace;

	/**
	 * @var string
	 */
	protected $dimensionsHash;

	/**
	 * @param AbstractEvent $eventObject
	 */
	public function __construct(AbstractEvent $eventObject) {
		$this->timestamp = microtime(TRUE);
		$this->workspace = $eventObject->getWorkspace();
		$this->dimensionsHash = $eventObject->getDimensionsHash();
		$this->eventType = get_class($eventObject);
		$this->nodeIdentifier = $eventObject->getNodeReference()->identifier;
		$this->eventObject = json_encode($eventObject);
	}

	/**
	 * @return \JsonSerializable
	 */
	public function getEventObject() {
		return $this->eventObject;
	}

	/**
	 * @return float
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

}