CommandHandlers
===============

* Every command has a single handler
* A handler is configured by (in order of importance)

	* Configuration
	* Annotation
	* Naming convention

* Every command has just a single handler
* A handler is just a callable.

Possible callable formats:

	* String function name: 'myFunctionName'
	* String class + functioname: 'MyClass::myFunctionName'
	* Array, first index classname, second index methodname: ['MyClass', 'MyMethod']
	* Array, first index instance of object, second index methodName: [$object, 'MyMethod']
	* Anonymous function

The first 2 formats can be caught in yaml syntax, if we want to support the 3rd and 4th option
we need to find 1) a usecase, and 2) a way to configure it.

