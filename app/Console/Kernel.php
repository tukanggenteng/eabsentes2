<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        \App\Console\Commands\TiapHariCommand::class,
        \App\Console\Commands\CheckRaspberry::class,
        \App\Console\Commands\UpdatePegawai::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('TiapHariCommand:tambahabsen')
	             ->dailyAt('00:01');
	// ->everyMinute();
       // $schedule->call(function () {


//            Mail::to('emailsampah@emailuser.com')->send(new BlogPost());

  //      })->monthlyOn(1, '20:00');

    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
