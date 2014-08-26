<?php
namespace SimplyAdmire\CR\Domain\Events;

use SimplyAdmire\CR\Domain\Dto\NodeReference;

abstract class AbstractEvent implements \JsonSerializable {

	/**
	 * @var NodeReference
	 */
	protected $nodeReference;

	/**
	 * @var string
	 */
	protected $workspace;

	/**
	 * @var string
	 */
	protected $dimensionsHash;

	/**
	 * @param NodeReference $nodeReference
	 * @param string $workspace
	 * @param array $dimensions
	 */
	public function __construct(NodeReference $nodeReference, $workspace, array $dimensions) {
		$this->nodeReference = $nodeReference;
		$this->workspace = $workspace;
		$this->buildDimensionValues($dimensions);
	}

	/**
	 * @return NodeReference
	 */
	public function getNodeReference() {
		return $this->nodeReference;
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