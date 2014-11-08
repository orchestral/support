<?php namespace Orchestra\Support\Traits;

use Illuminate\Support\Facades\App;
use Orchestra\Support\Facades\Messages;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ControllerResponseTrait
{
    /**
     * Queue notification and redirect.
     *
     * @param  string   $to
     * @param  string   $message
     * @param  string   $type
     * @return mixed
     */
    public function redirectWithMessage($to, $message = null, $type = 'success')
    {
        ! is_null($message) && Messages::add($type, $message);

        return $this->redirect($to);
    }

    /**
     * Queue notification and redirect.
     *
     * @param  string   $to
     * @param  mixed    $errors
     * @return mixed
     */
    public function redirectWithErrors($to, $errors)
    {
        return $this->redirect($to)->withInput()->withErrors($errors);
    }

    /**
     * Redirect.
     *
     * @param  string   $to
     * @return mixed
     */
    public function redirect($to)
    {
        return Redirect::to($to);
    }

    /**
     * Halt current request using App::abort().
     *
     * @param  int   $code
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function suspend($code, $message = '', array $headers = [])
    {
        if ($code == 404) {
            throw new NotFoundHttpException($message);
        }

        throw new HttpException($code, $message, null, $headers);
    }
}
