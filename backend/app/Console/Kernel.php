<?php

namespace App\Console;

use Api\Generators\Action\GenerateAction;
use Api\Generators\Controller\GenerateController;
use Api\Generators\Model\GenerateModel;
use Api\Generators\Policy\GeneratePolicy;
use Api\Generators\Request\GenerateRequest;
use Api\Settings\Settings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var array
     */
    protected $commands = [
        GenerateController::class,
        GenerateModel::class,
        GeneratePolicy::class,
        GenerateRequest::class,
        GenerateAction::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $settings = app(Settings::class);

        if ($settings->get('news.auto_update')) {
            $schedule->command('news:update')->daily();
        }

        if (config('common.site.demo')) {
            $schedule->command('demo:clean')->daily();
        }

        $schedule->command('lists:update')->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
