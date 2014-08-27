SimplyAdmire.CR
===============

This package is a wrapper package for TYPO3.TYPO3CR implementing
CQRS and Event sourcing on top of TYPO3CR. If you use this package
than this is on your own risk. We aim for having the same api as
TYPO3.TYPO3CR but don't give any guarantees.

Many many thanks to @tmaroschik for sharing knowledge, brainstorming,
rubber ducking and just for being @tmaroschik ;)

Further reading
---------------

* http://cqrs.nu/
* http://blog.jonathanoliver.com/cqrs-sagas-with-event-sourcing-part-i-of-ii/
* http://blog.jonathanoliver.com/cqrs-sagas-with-event-sourcing-part-ii-of-ii/
* http://abdullin.com/post/event-sourcing-projections/
* http://abdullin.com/tags/cqrs/
* http://de.slideshare.net/cavalle/the-cqrs-diet?from_search=2
* https://github.com/qandidate-labs/broadway

![Some scary art](https://www.dropbox.com/s/6cicsg9bk8d89z4/Screenshot%202014-08-26%2019.27.37.png?dl=0)

Basic flow for creating a node
==============================

* Create a `CreateNodeCommand`
* Pass it to the `CommandBus->handle()`
* The `CommandHandler` handles the command by calling the write model
* The `CommandHandler` fetches the events to emit from the write model
* The `CommandHandler` stores the events and call the `apply` method on the write model
* The `CommandHandler` emits the event on the `EventBus`
* `NodeProjection->onNodeCreated` listens to the `NodeCreatedEvent` and creates the node
* Anyone else willing to listen listens to the event on the `EventBus`

Auto created child nodes
---------------------------------

For creating the auto generated child nodes an event listener is registered that will create a `saga` by calling the
`SagaFactory`. This saga is a 'long' running process consisting of multiple commands. In this case for every auto
created child node a new command is issued and passed to the bus, as soon as all events for the direct auto created
child nodes of the created nodes are emitted a `AllAutoCreatedChildNodesCreatedEvent` is emitted.

Terms & stuff
==========

*CQRS*
`Command/Query Responsibility Segregation`: http://cqrs.nu/Faq/command-query-responsibility-segregation

*Event Sourcing*
http://cqrs.nu/Faq/event-sourcing

*Command*
A command is basically a request for change. A command can be refused. In this package we decided to keep the commands
simple objects with public properties for easier handling. This could change in the future though.
Commands are named with a verb in imperative mood: http://en.wikipedia.org/wiki/Imperative_mood

*Aggregate*
http://cqrs.nu/Faq/aggregates

*CommandHandler*
* Validates the command object
* Validates the command on the current state of the aggregate
* Persists and emits the events

*CommandBus*
* Receives commands
* Executes commands

*Event*
Events represent something that has happened in the domain. Events are named with past-particle verb. An event is immutable,
and stays around till end of time or till the sysadmin makes a horrible mistake.

*EventBus*
* Receives events
* Emits events

*Projection*
http://abdullin.com/post/event-sourcing-projections/

*Saga*
A 'long' running process.

This is a cite from one of the CQRS leaders:

> For starters, what is a saga?  A saga is a “long-lived business transaction or process”.  Okay, so what does that mean?
> Well, first of all, the “long-lived” part doesn’t have to mean hours, days, or even weeks—it could literally mean
> something as short as a few seconds.  The amount of time is not the important part.  It’s the fact that the
> “transaction”, business process, or activity spans more than one message.

*Write model*
A specialized model for writing operations, uses specialized method names.

*Read model*
A model specialized for reading. Contains only the data needed to fetch for performance reading.

Configuration
==========

...

