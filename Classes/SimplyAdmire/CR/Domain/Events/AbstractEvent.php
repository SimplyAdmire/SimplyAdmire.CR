<?php
namespace SimplyAdmire\CR\Domain\Events;

use SimplyAdmire\CR\Domain\Dto\NodeReference;

abstract class AbstractEvent {

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
	 * @var array
	 */
	protected $dimensions;

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
	 * @return array
	 */
	public function getDimensions() {
		return $this->dimensions;
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
		$this->dimensions = $dimensionValues;
		$this->dimensionsHash = md5(json_encode($dimensionValues));
	}

}