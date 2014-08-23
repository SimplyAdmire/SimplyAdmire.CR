<?php
namespace SimplyAdmire\CR\Annotations;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class CommandHandler {

	/**
	 * @var string
	 */
	public $callable;

}
