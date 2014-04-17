<?php namespace Orchestra\Support\Traits;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use Orchestra\Support\Facades\Messages;

trait ControllerResponseTrait
{
    /**
     * Queue notification and redirect.
     *
     * @param  string  $to
     * @param  string  $message
     * @param  string  $type
     * @return Response
     */
    public function redirectWithMessage($to, $message = null, $type = 'success')
    {
        ! is_null($message) && Messages::add($type, $message);

        return $this->redirect($to);
    }

    /**
     * Queue notification and redirect.
     *
     * @param  string  $to
     * @param  mixed   $errors
     * @return Response
     */
    public function redirectWithErrors($to, $errors)
    {
        return $this->redirect($to)->withInput()->withErrors($errors);
    }

    /**
     * Redirect.
     *
     * @param  string  $to
     * @param  string  $message
     * @param  string  $type
     * @return Response
     */
    public function redirect($to)
    {
        return Redirect::to($to);
    }

    /**
     * Halt current request using App::abort().
     *
     * @param  integer $status
     * @return Response
     */
    public function suspend($status)
    {
        return App::abort($status);
    }
}
