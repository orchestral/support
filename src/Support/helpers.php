<?php

if (! function_exists('redirect_with_errors')) {
    /**
     * Redirect with input and errors.
     *
     * @param  string  $to
     * @param  \Illuminate\Support\MessageBag|array  $errors
     * @return mixed
     */
    function redirect_with_errors($to, $errors)
    {
        return redirect($to)->withInput()->withErrors($errors);
    }
}

if (! function_exists('redirect_with_message')) {
    /**
     * Queue notification and redirect.
     *
     * @param  string  $to
     * @param  string  $message
     * @param  string  $type
     * @return mixed
     */
    function redirect_with_message($to, $message = null, $type = 'success')
    {
        ! is_null($message) && app('orchestra.messages')->add($type, $message);

        return redirect($to);
    }
}
