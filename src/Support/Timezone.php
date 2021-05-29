<?php

namespace Orchestra\Support;

use Carbon\Carbon;

class Timezone
{
    /**
     * List of timezones.
     *
     * @var array
     */
    protected static $lists = [
        [
            'offset' => '-1100',
            'code' => 'Pacific/Midway',
            'name' => '(GMT-11:00) Midway Island',
        ],
        [
            'offset' => '-1100',
            'code' => 'US/Samoa',
            'name' => '(GMT-11:00) Samoa',
        ],
        [
            'offset' => '-1000',
            'code' => 'US/Hawaii',
            'name' => '(GMT-10:00) Hawaii',
        ],
        [
            'offset' => '-0900',
            'code' => 'US/Alaska',
            'name' => '(GMT-09:00) Alaska',
        ],
        [
            'offset' => '-0800',
            'code' => 'US/Pacific',
            'name' => '(GMT-08:00) Pacific Time (US &amp; Canada)',
        ],
        [
            'offset' => '-0800',
            'code' => 'America/Tijuana',
            'name' => '(GMT-08:00) Tijuana',
        ],
        [
            'offset' => '-0700',
            'code' => 'US/Arizona',
            'name' => '(GMT-07:00) Arizona',
        ],
        [
            'offset' => '-0700',
            'code' => 'US/Mountain',
            'name' => '(GMT-07:00) Mountain Time (US &amp; Canada)',
        ],
        [
            'offset' => '-0700',
            'code' => 'America/Chihuahua',
            'name' => '(GMT-07:00) Chihuahua',
        ],
        [
            'offset' => '-0700',
            'code' => 'America/Mazatlan',
            'name' => '(GMT-07:00) Mazatlan',
        ],
        [
            'offset' => '-0600',
            'code' => 'America/Mexico_City',
            'name' => '(GMT-06:00) Mexico City',
        ],
        [
            'offset' => '-0600',
            'code' => 'America/Monterrey',
            'name' => '(GMT-06:00) Monterrey',
        ],
        [
            'offset' => '-0600',
            'code' => 'Canada/Saskatchewan',
            'name' => '(GMT-06:00) Saskatchewan',
        ],
        [
            'offset' => '-0600',
            'code' => 'US/Central',
            'name' => '(GMT-06:00) Central Time (US &amp; Canada)',
        ],
        [
            'offset' => '-0500',
            'code' => 'US/Eastern',
            'name' => '(GMT-05:00) Eastern Time (US &amp; Canada)',
        ],
        [
            'offset' => '-0500',
            'code' => 'US/East-Indiana',
            'name' => '(GMT-05:00) Indiana (East)',
        ],
        [
            'offset' => '-0500',
            'code' => 'America/Bogota',
            'name' => '(GMT-05:00) Bogota',
        ],
        [
            'offset' => '-0500',
            'code' => 'America/Lima',
            'name' => '(GMT-05:00) Lima',
        ],
        [
            'offset' => '-0430',
            'code' => 'America/Caracas',
            'name' => '(GMT-04:30) Caracas',
        ],
        [
            'offset' => '-0400',
            'code' => 'Canada/Atlantic',
            'name' => '(GMT-04:00) Atlantic Time (Canada)',
        ],
        [
            'offset' => '-0400',
            'code' => 'America/La_Paz',
            'name' => '(GMT-04:00) La Paz',
        ],
        [
            'offset' => '-0400',
            'code' => 'America/Santiago',
            'name' => '(GMT-04:00) Santiago',
        ],
        [
            'offset' => '-0330',
            'code' => 'Canada/Newfoundland',
            'name' => '(GMT-03:30) Newfoundland',
        ],
        [
            'offset' => '-0300',
            'code' => 'America/Buenos_Aires',
            'name' => '(GMT-03:00) Buenos Aires',
        ],
        [
            'offset' => '-0300',
            'code' => 'Greenland',
            'name' => '(GMT-03:00) Greenland',
        ],
        [
            'offset' => '-0200',
            'code' => 'Atlantic/Stanley',
            'name' => '(GMT-02:00) Stanley',
        ],
        [
            'offset' => '-0100',
            'code' => 'Atlantic/Azores',
            'name' => '(GMT-01:00) Azores',
        ],
        [
            'offset' => '-0100',
            'code' => 'Atlantic/Cape_Verde',
            'name' => '(GMT-01:00) Cape Verde Is.',
        ],
        [
            'offset' => 'UTC',
            'code' => 'UTC',
            'name' => 'Coordinated Universal Time (UTC)',
        ],
        [
            'offset' => 'GMT',
            'code' => 'Africa/Casablanca',
            'name' => '(GMT) Casablanca',
        ],
        [
            'offset' => 'GMT',
            'code' => 'Europe/Dublin',
            'name' => '(GMT) Dublin',
        ],
        [
            'offset' => 'GMT',
            'code' => 'Europe/Lisbon',
            'name' => '(GMT) Lisbon',
        ],
        [
            'offset' => 'GMT',
            'code' => 'Europe/London',
            'name' => '(GMT) London',
        ],
        [
            'offset' => 'GMT',
            'code' => 'Africa/Monrovia',
            'name' => '(GMT) Monrovia',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Amsterdam',
            'name' => '(GMT+01:00) Amsterdam',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Belgrade',
            'name' => '(GMT+01:00) Belgrade',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Berlin',
            'name' => '(GMT+01:00) Berlin',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Bratislava',
            'name' => '(GMT+01:00) Bratislava',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Brussels',
            'name' => '(GMT+01:00) Brussels',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Budapest',
            'name' => '(GMT+01:00) Budapest',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Copenhagen',
            'name' => '(GMT+01:00) Copenhagen',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Ljubljana',
            'name' => '(GMT+01:00) Ljubljana',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Madrid',
            'name' => '(GMT+01:00) Madrid',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Paris',
            'name' => '(GMT+01:00) Paris',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Prague',
            'name' => '(GMT+01:00) Prague',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Rome',
            'name' => '(GMT+01:00) Rome',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Sarajevo',
            'name' => '(GMT+01:00) Sarajevo',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Skopje',
            'name' => '(GMT+01:00) Skopje',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Stockholm',
            'name' => '(GMT+01:00) Stockholm',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Vienna',
            'name' => '(GMT+01:00) Vienna',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Warsaw',
            'name' => '(GMT+01:00) Warsaw',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Zagreb',
            'name' => '(GMT+01:00) Zagreb',
        ],
        [
            'offset' => '+0100',
            'code' => 'Europe/Athens',
            'name' => '(GMT+02:00) Athens',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Bucharest',
            'name' => '(GMT+02:00) Bucharest',
        ],
        [
            'offset' => '+0200',
            'code' => 'Africa/Cairo',
            'name' => '(GMT+02:00) Cairo',
        ],
        [
            'offset' => '+0200',
            'code' => 'Africa/Harare',
            'name' => '(GMT+02:00) Harare',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Helsinki',
            'name' => '(GMT+02:00) Helsinki',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Istanbul',
            'name' => '(GMT+02:00) Istanbul',
        ],
        [
            'offset' => '+0200',
            'code' => 'Asia/Jerusalem',
            'name' => '(GMT+02:00) Jerusalem',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Kiev',
            'name' => '(GMT+02:00) Kyiv',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Minsk',
            'name' => '(GMT+02:00) Minsk',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Riga',
            'name' => '(GMT+02:00) Riga',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Sofia',
            'name' => '(GMT+02:00) Sofia',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Tallinn',
            'name' => '(GMT+02:00) Tallinn',
        ],
        [
            'offset' => '+0200',
            'code' => 'Europe/Vilnius',
            'name' => '(GMT+02:00) Vilnius',
        ],
        [
            'offset' => '+0300',
            'code' => 'Asia/Baghdad',
            'name' => '(GMT+03:00) Baghdad',
        ],
        [
            'offset' => '+0300',
            'code' => 'Asia/Kuwait',
            'name' => '(GMT+03:00) Kuwait',
        ],
        [
            'offset' => '+0300',
            'code' => 'Africa/Nairobi',
            'name' => '(GMT+03:00) Nairobi',
        ],
        [
            'offset' => '+0300',
            'code' => 'Asia/Riyadh',
            'name' => '(GMT+03:00) Riyadh',
        ],
        [
            'offset' => '+0330',
            'code' => 'Asia/Tehran',
            'name' => '(GMT+03:30) Tehran',
        ],
        [
            'offset' => '+0400',
            'code' => 'Europe/Moscow',
            'name' => '(GMT+04:00) Moscow',
        ],
        [
            'offset' => '+0400',
            'code' => 'Asia/Baku',
            'name' => '(GMT+04:00) Baku',
        ],
        [
            'offset' => '+0400',
            'code' => 'Europe/Volgograd',
            'name' => '(GMT+04:00) Volgograd',
        ],
        [
            'offset' => '+0400',
            'code' => 'Asia/Muscat',
            'name' => '(GMT+04:00) Muscat',
        ],
        [
            'offset' => '+0400',
            'code' => 'Asia/Tbilisi',
            'name' => '(GMT+04:00) Tbilisi',
        ],
        [
            'offset' => '+0400',
            'code' => 'Asia/Yerevan',
            'name' => '(GMT+04:00) Yerevan',
        ],
        [
            'offset' => '+0430',
            'code' => 'Asia/Kabul',
            'name' => '(GMT+04:30) Kabul',
        ],
        [
            'offset' => '+0500',
            'code' => 'Asia/Karachi',
            'name' => '(GMT+05:00) Karachi',
        ],
        [
            'offset' => '+0500',
            'code' => 'Asia/Tashkent',
            'name' => '(GMT+05:00) Tashkent',
        ],
        [
            'offset' => '+0530',
            'code' => 'Asia/Kolkata',
            'name' => '(GMT+05:30) Kolkata',
        ],
        [
            'offset' => '+0545',
            'code' => 'Asia/Kathmandu',
            'name' => '(GMT+05:45) Kathmandu',
        ],
        [
            'offset' => '+0600',
            'code' => 'Asia/Yekaterinburg',
            'name' => '(GMT+06:00) Ekaterinburg',
        ],
        [
            'offset' => '+0600',
            'code' => 'Asia/Almaty',
            'name' => '(GMT+06:00) Almaty',
        ],
        [
            'offset' => '+0600',
            'code' => 'Asia/Dhaka',
            'name' => '(GMT+06:00) Dhaka',
        ],
        [
            'offset' => '+0700',
            'code' => 'Asia/Novosibirsk',
            'name' => '(GMT+07:00) Novosibirsk',
        ],
        [
            'offset' => '+0700',
            'code' => 'Asia/Bangkok',
            'name' => '(GMT+07:00) Bangkok',
        ],
        [
            'offset' => '+0700',
            'code' => 'Asia/Jakarta',
            'name' => '(GMT+07:00) Jakarta',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Krasnoyarsk',
            'name' => '(GMT+08:00) Krasnoyarsk',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Chongqing',
            'name' => '(GMT+08:00) Chongqing',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Hong_Kong',
            'name' => '(GMT+08:00) Hong Kong',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Kuala_Lumpur',
            'name' => '(GMT+08:00) Kuala Lumpur',
        ],
        [
            'offset' => '+0800',
            'code' => 'Australia/Perth',
            'name' => '(GMT+08:00) Perth',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Singapore',
            'name' => '(GMT+08:00) Singapore',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Taipei',
            'name' => '(GMT+08:00) Taipei',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Ulaanbaatar',
            'name' => '(GMT+08:00) Ulaan Bataar',
        ],
        [
            'offset' => '+0800',
            'code' => 'Asia/Urumqi',
            'name' => '(GMT+08:00) Urumqi',
        ],
        [
            'offset' => '+0900',
            'code' => 'Asia/Irkutsk',
            'name' => '(GMT+09:00) Irkutsk',
        ],
        [
            'offset' => '+0900',
            'code' => 'Asia/Seoul',
            'name' => '(GMT+09:00) Seoul',
        ],
        [
            'offset' => '+0900',
            'code' => 'Asia/Tokyo',
            'name' => '(GMT+09:00) Tokyo',
        ],
        [
            'offset' => '+0930',
            'code' => 'Australia/Adelaide',
            'name' => '(GMT+09:30) Adelaide',
        ],
        [
            'offset' => '+0930',
            'code' => 'Australia/Darwin',
            'name' => '(GMT+09:30) Darwin',
        ],
        [
            'offset' => '+1000',
            'code' => 'Asia/Yakutsk',
            'name' => '(GMT+10:00) Yakutsk',
        ],
        [
            'offset' => '+1000',
            'code' => 'Australia/Brisbane',
            'name' => '(GMT+10:00) Brisbane',
        ],
        [
            'offset' => '+1000',
            'code' => 'Australia/Canberra',
            'name' => '(GMT+10:00) Canberra',
        ],
        [
            'offset' => '+1000',
            'code' => 'Pacific/Guam',
            'name' => '(GMT+10:00) Guam',
        ],
        [
            'offset' => '+1000',
            'code' => 'Australia/Hobart',
            'name' => '(GMT+10:00) Hobart',
        ],
        [
            'offset' => '+1000',
            'code' => 'Australia/Melbourne',
            'name' => '(GMT+10:00) Melbourne',
        ],
        [
            'offset' => '+1000',
            'code' => 'Pacific/Port_Moresby',
            'name' => '(GMT+10:00) Port Moresby',
        ],
        [
            'offset' => '+1000',
            'code' => 'Australia/Sydney',
            'name' => '(GMT+10:00) Sydney',
        ],
        [
            'offset' => '+1100',
            'code' => 'Asia/Vladivostok',
            'name' => '(GMT+11:00) Vladivostok',
        ],
        [
            'offset' => '+1200',
            'code' => 'Asia/Magadan',
            'name' => '(GMT+12:00) Magadan',
        ],
        [
            'offset' => '+1200',
            'code' => 'Pacific/Auckland',
            'name' => '(GMT+12:00) Auckland',
        ],
        [
            'offset' => '+1200',
            'code' => 'Pacific/Fiji',
            'name' => '(GMT+12:00) Fiji',
        ],
    ];

    /**
     * Timezones midnight by now.
     *
     * @return \Orchestra\Support\Collection
     */
    public static function now(): Collection
    {
        return static::whereHourInUtc(Carbon::now()->hour);
    }

    /**
     * Timezones offset by hours.
     *
     * @param  int  $hour
     * @param  \DateTimeZone|string|null  $timezone
     *
     * @return \Orchestra\Support\Collection
     */
    public static function at(int $hour, $timezone = null): Collection
    {
        return static::whereHourInUtc(Carbon::now($timezone)->subHours($hour)->timezone('UTC')->hour);
    }

    /**
     * Timezones by given UTC hour.
     *
     * @param  int  $hourInUtc
     *
     * @return \Orchestra\Support\Collection
     */
    public static function whereHourInUtc(int $hourInUtc): Collection
    {
        $where = ['UTC', 'GMT'];

        if ($hourInUtc > 12) {
            $offsetHour = str_pad((24 - $hourInUtc), 2, '0', STR_PAD_LEFT);
            $beforeOffsetHour = str_pad(((24 - $hourInUtc) - 1), 2, '0', STR_PAD_LEFT);

            $where = ["+{$offsetHour}00", "+{$beforeOffsetHour}30", "+{$beforeOffsetHour}45"];
        } elseif ($hourInUtc > 0) {
            $offsetHour = str_pad($hourInUtc, 2, '0', STR_PAD_LEFT);

            $where = ["-{$offsetHour}00", "-{$offsetHour}30", "-{$offsetHour}45"];
        }

        return static::whereIn('offset', $where)->pluck('code');
    }

    /**
     * Timezones by given UTC hour.
     *
     * @param  int  $hourInUtc
     *
     * @return \Orchestra\Support\Collection
     */
    public static function on(int $hourInUtc): Collection
    {
        return static::whereHourInUtc($hourInUtc);
    }

    /**
     * Get all timezones as collection.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public static function __callStatic($method, array $parameters)
    {
        return Collection::make(static::$lists)->{$method}(...$parameters);
    }
}
