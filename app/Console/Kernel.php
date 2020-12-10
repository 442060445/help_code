<?php

namespace App\Console;

use App\Console\Commands\ResetDailyCommand;
use App\Console\Commands\ResetWeeklyCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        ResetDailyCommand::class,  //每日置零脚本
        ResetWeeklyCommand::class  // 周期性置零
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //
    }
}
