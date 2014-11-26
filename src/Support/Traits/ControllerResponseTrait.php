<?php namespace Orchestra\Support\Traits;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ControllerResponseTrait
{
    /**
     * Queue notification and redirect.
     *
     * @param  string  $to
     * @param  string  $message
     * @param  string  $type
     * @return mixed
     */
    public function redirectWithMessage($to, $message = null, $type = 'success')
    {
        return redirect_with_message($to, $message, $type);
    }

    /**
     * Redirect with input and errors.
     *
     * @param  string  $to
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @return mixed
     */
    public function redirectWithErrors($to, $errors)
    {
        return redirect_with_errors($to, $errors);
    }

    /**
     * Redirect.
     *
     * @param  string  $to
     * @return mixed
     */
    public function redirect($to)
    {
        return redirect($to);
    }

    /**
     * Halt current request using App::abort().
     *
     * @param  int     $code
     * @param  string  $message
     * @param  array   $headers
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
