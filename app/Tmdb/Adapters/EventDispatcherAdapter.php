<?php

namespace App\Tmdb\Adapters;

use Illuminate\Contracts\Events\Dispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface as SymfonyDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * This adapter provides a Laravel integration for applications
 * using the Symfony EventDispatcherInterface
 * It passes any request on to a Symfony Dispatcher and only
 * uses the Laravel Dispatcher when dispatching events.
 */
abstract class EventDispatcherAdapter implements SymfonyDispatcher
{
    /**
     * The Laravel Events Dispatcher.
     *
     * @var Dispatcher or \Illuminate\Events\Dispatcher
     */
    protected $laravelDispatcher;

    /**
     * The Symfony Event Dispatcher.
     *
     * @var  SymfonyDispatcher
     */
    protected $symfonyDispatcher;

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param  object  $event The event to pass to the event handlers/listeners.
     *                          If not supplied, an empty Event instance is created.
     * @param  string|null  $eventName The name of the event to dispatch. The name of
     *                          the event is the name of the method that is
     *                          invoked on listeners.
     *
     * @api
     */
    public function dispatch(object $event, string $eventName = null): object
    {
        $this->laravelDispatcher->dispatch($event);

        return $this->symfonyDispatcher->dispatch($event, $eventName);
    }

    /**
     * Adds an event listener that listens on the specified events.
     *
     * @param  string  $eventName The event to listen on
     * @param  callable  $listener The listener
     * @param  int  $priority The higher this value, the earlier an event
     *                            listener will be triggered in the chain (defaults to 0)
     *
     * @api
     */
    public function addListener($eventName, $listener, $priority = 0)
    {
        $this->symfonyDispatcher->addListener($eventName, $listener, $priority);
    }

    /**
     * Adds an event subscriber.
     *
     * The subscriber is asked for all the events he is
     * interested in and added as a listener for these events.
     *
     * @param  EventSubscriberInterface  $subscriber The subscriber.
     *
     * @api
     */
    public function addSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->symfonyDispatcher->addSubscriber($subscriber);
    }

    /**
     * Removes an event listener from the specified events.
     *
     * @param  string  $eventName The event to remove a listener from
     * @param  callable  $listenerToBeRemoved The listener to remove
     */
    public function removeListener($eventName, $listenerToBeRemoved)
    {
        $this->symfonyDispatcher->removeListener($eventName, $listenerToBeRemoved);
    }

    /**
     * Removes an event subscriber.
     *
     * @param  EventSubscriberInterface  $subscriber The subscriber
     */
    public function removeSubscriber(EventSubscriberInterface $subscriber)
    {
        $this->symfonyDispatcher->removeSubscriber($subscriber);
    }

    /**
     * Gets the listeners of a specific event or all listeners.
     *
     * @param  string  $eventName The name of the event
     * @return array The event listeners for the specified event, or all event listeners by event name
     */
    public function getListeners($eventName = null): array
    {
        return $this->symfonyDispatcher->getListeners($eventName);
    }

    /**
     * Checks whether an event has any registered listeners.
     *
     * @param  string  $eventName The name of the event
     * @return bool true if the specified event has any listeners, false otherwise
     */
    public function hasListeners($eventName = null): bool
    {
        return $this->symfonyDispatcher->hasListeners($eventName) ||
            $this->laravelDispatcher->hasListeners($eventName);
    }

    /**
     * Gets the listener priority for a specific event.
     *
     * Returns null if the event or the listener does not exist.
     *
     * @param  string  $eventName The name of the event
     * @param  callable  $listener The listener
     * @return int|null The event listener priority
     */
    public function getListenerPriority($eventName, $listener): ?int
    {
        return $this->symfonyDispatcher->getListenerPriority($eventName, $listener);
    }
}
