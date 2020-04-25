<?php

namespace Telegram\Bot\Events;

use Closure;

/**
 * Class EventFactory
 */
class EventFactory
{
    use InteractsWithEvents;

    protected array $listens;
    protected array $subscribers;

    /**
     * EventFactory constructor.
     *
     * @param array $listens
     */
    public function __construct(array $listens)
    {
        $this->listens = $listens;
    }

    /**
     * Get the events and handlers.
     *
     * @return array
     */
    public function listens(): array
    {
        return $this->listens;
    }

    /**
     * Set the events and handlers.
     *
     * @param array $listeners
     *
     * @return static
     */
    public function setListeners(array $listeners): self
    {
        $this->listens = $listeners;

        return $this;
    }

    /**
     * @return array
     */
    public function subscribers(): array
    {
        return $this->subscribers;
    }

    /**
     * @param array $subscribe
     *
     * @return static
     */
    public function setSubscriber(array $subscribe): self
    {
        $this->subscribers[] = $subscribe;

        return $this;
    }

    /**
     * Register listerners for events.
     */
    public function registerListerners(): void
    {
        $events = $this->listens();

        foreach ($events as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                $this->listen($event, $listener);
            }
        }

        foreach ($this->subscribers as $subscriber) {
            $this->subscribe($subscriber);
        }
    }

    /**
     * Register an event listener with the dispatcher.
     *
     * @param string|array   $events
     * @param Closure|string $listener
     */
    public function listen($events, $listener): void
    {
        $this->getDispatcher()->listen($events, $listener);
    }

    /**
     * Register an event subscriber with the dispatcher.
     *
     * @param object|string $subscriber
     */
    public function subscribe($subscriber): void
    {
        $this->getDispatcher()->subscribe($subscriber);
    }

    /**
     * Dispatch an event and call the listeners.
     *
     * @param string|object $event
     * @param mixed         $payload
     * @param bool          $halt
     *
     * @return array|null
     */
    public function dispatch($event, $payload = [], $halt = false): ?array
    {
        return $this->getDispatcher()->dispatch($event, $payload, $halt);
    }

    public function __call($method, $params)
    {
        return $this->getDispatcher()->{$method}(...$params);
    }
}
