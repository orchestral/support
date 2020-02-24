<?php

namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method \Orchestra\View\Theme\Theme initiate()
 * @method bool boot()
 * @method bool resolving()
 * @method void setTheme(?string $theme)
 * @method string getTheme()
 * @method string getThemePath()
 * @method string getCascadingThemePath()
 * @method array getThemePaths()
 * @method array getAvailableThemePaths()
 * @method string to(string $url = '')
 * @method string asset(string $url = '')
 * @method \Illuminate\Support\Collection detect()
 *
 * @see \Orchestra\View\Theme\ThemeManager
 */
class Theme extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.theme';
    }
}
