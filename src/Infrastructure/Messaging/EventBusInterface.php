<?php

namespace Qtvhao\CourseManagement\Infrastructure\Messaging;

/**
 * Interface for an Event Bus to publish and subscribe to domain events.
 */
interface EventBusInterface
{
    /**
     * Publish a single event to be handled by subscribers.
     *
     * @param object $event The domain event to publish.
     * @return void
     */
    public function publish(object $event): void;

    /**
     * Register an event handler for a specific event type.
     *
     * @param string $eventClass The fully qualified class name of the event.
     * @param callable $handler A handler function or class method for the event.
     * @return void
     */
    public function subscribe(string $eventClass, callable $handler): void;
}
