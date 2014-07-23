<?php
namespace SimplyAdmire\CR\Tests\Unit;

use TYPO3\Flow\Tests\UnitTestCase;

class CallableTest extends UnitTestCase {

	/**
	 * @param callable $callable
	 * @param string $argument
	 * @return mixed
	 */
	protected function callCallable($callable, $argument) {
		return call_user_func($callable, $argument);
	}

	/**
	 * @return array
	 */
	public function validCallablesDataProvider() {
		$temporaryClassName1 = uniqid('CallableClass');
		$temporaryClassName2 = uniqid('CallableClass');

		eval(<<<CALLABLES
			class $temporaryClassName1 {
				static public function bar(\$arg) {
					return strtoupper(\$arg);
				}
			}
			class $temporaryClassName2 {
				public function __invoke(\$arg) {
					return strtoupper(\$arg);
				}
			}
CALLABLES
		);

		return [
			[
				array($temporaryClassName1, 'bar'),
				'foo1', 'FOO1',
				'["ClassName", "method"]'
			], [
				$temporaryClassName1 . '::bar',
				'foo2', 'FOO2',
				'"ClassName::method"'
			], [
				array(new $temporaryClassName1, 'bar'),
				'foo3', 'FOO3',
				'[$instance, "method"]'
			], [
				new $temporaryClassName2,
				'foo4', 'FOO4',
				'$invokableInstance'
			], 	[
				'trim',
				' foo5 ', 'foo5',
				"functionName"
			], [
				function($argument) { return ucfirst($argument); },
				'foo6', 'Foo6',
				'Anonymous function'
			]

		];
	}

	/**
	 * @test
	 * @dataProvider validCallablesDataProvider
	 */
	public function testValidCallables($callable, $argument, $expected, $message) {
		$this->assertEquals($expected, $this->callCallable($callable, $argument), $message);
	}
}