<?php
namespace SimplyAdmire\CR\Domain\Events;

use SimplyAdmire\CR\Domain\Dto\NodePointer;

abstract class AbstractEvent implements \JsonSerializable {

	/**
	 * @var NodePointer
	 */
	protected $nodePointer;

	/**
	 * @var string
	 */
	protected $workspace;

	/**
	 * @var string
	 */
	protected $dimensionsHash;

	/**
	 * @param NodePointer $nodePointer
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct(NodePointer $nodePointer, $workspace, array $dimensions) {
		$this->nodePointer = $nodePointer;
		$this->workspace = $workspace;
		$this->buildDimensionValues($dimensions);
	}

	/**
	 * @return NodePointer
	 */
	public function getNodePointer() {
		return $this->nodePointer;
	}

	/**
	 * @return string
	 */
	public function getWorkspace() {
		return $this->workspace;
	}

	/**
	 * @return string
	 */
	public function getDimensionsHash() {
		return $this->dimensionsHash;
	}

	/**
	 * Build a cached array of dimension values and a hash to search for it.
	 *
	 * @return void
	 */
	protected function buildDimensionValues(array $dimensions) {
		$dimensionValues = array();
		foreach ($dimensions as $dimensionName => $dimensionValue) {
			$dimensionValues[$dimensionName][] = $dimensionValue;
		}
		foreach ($dimensionValues as &$values) {
			sort($values);
		}

		ksort($dimensionValues);
		$this->dimensionsHash = md5(json_encode($dimensionValues));
	}

	/**
	 * @return array
	 */
	public function jsonSerialize() {
		return [
			'__type' => get_class($this),
			'object_vars' => get_object_vars($this)
		];
	}

}