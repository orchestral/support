<?php

namespace Orchestra\Support\Tests;

use Carbon\Carbon;
use Orchestra\Support\Timezone;
use PHPUnit\Framework\TestCase as PHPUnit;

class TimezoneTest extends PHPUnit
{
    /** @test */
    public function it_can_list_scheduled_timezone_daily_at()
    {
        $this->assertSame([
            'UTC',
            'Africa/Casablanca',
            'Europe/Dublin',
            'Europe/Lisbon',
            'Europe/London',
            'Africa/Monrovia',
        ], Timezone::on(0)->all());

        $this->assertSame([
            'Atlantic/Azores',
            'Atlantic/Cape_Verde',
        ], Timezone::on(1)->all());

        $this->assertSame([
            'US/Pacific',
            'America/Tijuana',
        ], Timezone::on(8)->all());

        $this->assertSame([
            'Asia/Krasnoyarsk',
            'Asia/Chongqing',
            'Asia/Hong_Kong',
            'Asia/Kuala_Lumpur',
            'Australia/Perth',
            'Asia/Singapore',
            'Asia/Taipei',
            'Asia/Ulaanbaatar',
            'Asia/Urumqi',
        ], Timezone::on(16)->all());

        Carbon::setTestNow(Carbon::parse('2019-01-01 17:00:00', 'UTC'));

        $this->assertSame([
            'Asia/Novosibirsk',
            'Asia/Bangkok',
            'Asia/Jakarta',
        ], Timezone::now()->all());

        $this->assertSame([
            'Asia/Krasnoyarsk',
            'Asia/Chongqing',
            'Asia/Hong_Kong',
            'Asia/Kuala_Lumpur',
            'Australia/Perth',
            'Asia/Singapore',
            'Asia/Taipei',
            'Asia/Ulaanbaatar',
            'Asia/Urumqi',
        ], Timezone::at(1)->all());

        Carbon::setTestNow(Carbon::parse('2019-01-02 01:00:00', 'UTC'));

        $this->assertSame([
            'Atlantic/Azores',
            'Atlantic/Cape_Verde',
        ], Timezone::now()->all());

        $this->assertSame([
            'Asia/Krasnoyarsk',
            'Asia/Chongqing',
            'Asia/Hong_Kong',
            'Asia/Kuala_Lumpur',
            'Australia/Perth',
            'Asia/Singapore',
            'Asia/Taipei',
            'Asia/Ulaanbaatar',
            'Asia/Urumqi',
        ], Timezone::at(9)->all());

        Carbon::setTestNow(null);
    }
}
