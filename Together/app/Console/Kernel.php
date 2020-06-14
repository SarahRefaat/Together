<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        //------------------------ this to -1 from duration each day and if it is one update status with "last day"
        //$file='/home/administrator/Desktop/errors.txt';
        Log::info('Testing scheduler output');
        $schedule->call(function(){
            DB::table('groups')->where('duration',3)->delete();
            //DB::table('groups')
        })->everyMinute()->runInBackground();
        //------------------------- this to check if duration =1 set el status with last day
    //    $event = $schedule->call(function(){
     //       DB::table('groups')->update(['status','lastday']);
     //   })->everyMinute();
     //   dd($event->expression);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
