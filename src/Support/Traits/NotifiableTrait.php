<?php namespace Orchestra\Support\Traits;
 
use Ochestra\Model\User;
use Orchestra\Support\Facades\Notifier;
 
trait NotifiableTrait {
 
    /**
     * Send email notification to user
     * 
     * @param  Orchestra\Model\User   $user
     * @param  string $subject
     * @param  string $view
     * @param  array  $data
     * @return boolean
     */
    protected function sendNotification(User $user, $subject, $view, array $data)
    {
        $data = array_add($data, 'user', $user->toArray());
 
        $message = new Fluent([
            'subject' => $subject,
            'view' => $view,
            'data' => $data
        ]);
 
        $sent = Notifier::send($user, $message);
 
        return ($sent) ? true : false;
    }
 
}