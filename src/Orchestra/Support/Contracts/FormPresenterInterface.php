<?php namespace Orchestra\Support\Contracts;

use Orchestra\Html\Form\Grid;

interface FormPresenterInterface
{
    /**
     * Build form action URL.
     *
     * @param  string  $url
     * @return string
     */
    public function handles($url);

    /**
     * Setup form layout.
     *
     * @param  \Orchestra\Html\Form\Grid    $form
     * @return void
     */
    public function formLayout(Grid $form);
}
