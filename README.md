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

Some scary art
--------------

* http://www.websequencediagrams.com/?lz=dGl0bGUgQ1FSUyBOb2RlIENyZWF0aW9uIFNlcXVlbmNlCgpDb250cm9sbGVyLT5Db21tYW5kQnVzOiBpc3N1ZXMgYQAuBmVOb2RlABgHCgAdCi0-Tm9kZVdyaXRlUmVwb3NpdG9yeTogaGFuZGxlcwAoE29wdCBjAEgJCiAgIACBFAUAMw8AUQY6IGNvbnN0cnVjdHMgYSBuZXcgbgApDABmF3JldHVybnMAgWwFAIE2BmRFdmVudABTGgAaBVN0b3JlOiBzdG9yZQARLACBJAZhcHBsaQALLQCBDwUAgmUFZW1pdACBHBNlbmQKAB0IAIJiBlByb2plY3Rpb246IGxpc3RlbnMgdG8AgVcSbm90ZSByaWdodCBvZgCDdAUAMAoAgm4FY2hlY2tzIGlmIGhhAD4Fd2FpAIIaBmZvciBDaGlsZE5vZGVzAIIzDCwAgygFcGFya3MgdGhlAIMyBgCBIRAgbm90ZQCBJw9Xb3JrZmxvd0ZhYwCEGAYAgR8cAB4TLT4AgQgJAEMIOiBzcGF3bnMgaXQKAAwRAIJPDHJlZ2lzdGVycwCCKQdlcgCBXQVlYWNoAIQPESAoZmlsACcFYnkgZ2VuZXJhdGVkAIRuBSBpZGVudGlmaWVyKQBdFACGIxVjAIZHBgBrBnZlcnkgY2hpbABLBgCGCDsgaW4gb3B0IFsAhkEGAIdTBV0AhB4LAIIaE2NvbGxlAIZABWxsAIYGEXMAg28FAIdpBWQAh1oScwCCQh4AhTUGYQCEHBcAhSQbbm93IGNhbiBjYWxsAIgGCyBvbgCJJAVGYWNhZGUK&s=patent

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

For creating the auto generated child nodes an event listener is registered that will create a `saga` by calling the `SagaFactory`. This saga is a 'long' running process consisting of multiple commands. In this case for every auto created child node a new command is issued and passed to the bus, as soon as all events for the direct auto created child nodes of the created nodes are emitted a `AllAutoCreatedChildNodesCreatedEvent` is emitted.

Terms & stuff
==========

*CQRS*

*Event Sourcing*

*Command*

*CommandHandler*

*CommandBus*

*Event*

*EventBus*

*Projection*

*Saga*

*Write model*

*Read model*

Configuration
==========

...

