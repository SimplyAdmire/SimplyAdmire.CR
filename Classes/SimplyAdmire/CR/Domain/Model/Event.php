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
	 * @var string
	 */
	protected $correlationId;

	/**
	 * @param AbstractEvent $eventObject
	 */
	public function __construct(AbstractEvent $eventObject) {
		$this->timestamp = microtime(TRUE);
		$this->workspace = $eventObject->getWorkspace();
		$this->dimensionsHash = $eventObject->getDimensionsHash();
		$this->eventType = get_class($eventObject);
		$this->nodeIdentifier = $eventObject->getNodeReference()->identifier;
		$this->eventObject = serialize($eventObject);
	}

	/**
	 * @return AbstractEvent
	 */
	public function getEventObject() {
		return unserialize($this->eventObject);
	}

	/**
	 * @return float
	 */
	public function getTimestamp() {
		return $this->timestamp;
	}

	/**
	 * @return string
	 */
	public function getCorrelationId() {
		return $this->correlationId;
	}

	/**
	 * @param string $correlationId
	 * @return void
	 */
	public function setCorrelationId($correlationId) {
		$this->correlationId = $correlationId;
	}

	/**
	 * @return string
	 */
	public function getDimensionsHash() {
		return $this->dimensionsHash;
	}

	/**
	 * @return string
	 */
	public function getEventType() {
		return $this->eventType;
	}

	/**
	 * @return string
	 */
	public function getNodeIdentifier() {
		return $this->nodeIdentifier;
	}

	/**
	 * @return string
	 */
	public function getWorkspace() {
		return $this->workspace;
	}

}