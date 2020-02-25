<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\Notifier\PendingMail to(mixed $users)
 * @method \Orchestra\Notifier\PendingMail bcc(mixed $users)
 * @method \Orchestra\Contracts\Notification\Receipt push(\Illuminate\Contracts\Mail\Mailable|string|array $view, array $data = [], \Closure|string|null $callback = null, ?string $queue = null)
 * @method \Orchestra\Contracts\Notification\Recepit send(\Illuminate\Contracts\Mail\Mailable|string|array $view, array $data = [], \Closure|string|null $callback = null)
 * @method \Orchestra\Contracts\Notification\Receipt queue(\Illuminate\Contracts\Mail\Mailable|string|array $view, array $data = [], \Closure|string|null $callback = null, ?string $queue = null)
 * @method \Orchestra\Contracts\Notification\Receipt later(\DateInterval|int $delay, \Illuminate\Contracts\Mail\Mailable|string|array $view, array $data = [], \Closure|string|null $callback = null, ?string $queue = null)
 *
 * @see \Orchestra\Notifier\Postal
 */
class Mail extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.postal';
    }
}
