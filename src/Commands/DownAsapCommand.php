<?php

namespace Mrweb\DownAsap\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DownAsapCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'down:asap {--retry=} {--idle=}';

    private $deafultIdleMinutes  = 1;
    private $deafultRetrySeconds = 30;

    private $retryAfterSeconds, $waitForMinutes, $dbName, $minutesFromLastUpdate, $showTimePassed;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Put Laravel in maintenance mode once the DB is idle';

    private function init()
    {
        $this->retryAfterSeconds = (int) ($this->option('retry') ?? $this->deafultRetrySeconds);
        $this->waitForMinutes    = (int) ($this->option('idle') ?? $this->deafultIdleMinutes);

        $this->dbName = env('DB_DATABASE');
        if (!$this->dbName) {
            return $this->error("Database not set in .env file");
        }
    }

    private function checkLastDbMovement()
    {
        $this->lastDbUpdate = collect(\DB::select("select update_time from information_schema.tables where table_schema = '" . $this->dbName . "' order by update_time desc limit 1"))->first();

        $this->minutesFromLastUpdate = Carbon::parse($this->lastDbUpdate->update_time)->diffInMinutes(Carbon::now());
        if ($this->minutesFromLastUpdate < 1) {
            $secondsFromLastUpdate = Carbon::parse($this->lastDbUpdate->update_time)->diffInSeconds(Carbon::now());
        }
        $this->showTimePassed = ($this->minutesFromLastUpdate > 0) ? $this->minutesFromLastUpdate . ' minutes' : $secondsFromLastUpdate . ' seconds';
    }

    private function tryLoop()
    {
        $bar = $this->output->createProgressBar($this->retryAfterSeconds);
        $bar->start();
        for ($sec = 1; $sec <= $this->retryAfterSeconds; $sec++) {
            sleep(1);
            $bar->clear();
            $bar->advance();
            if ($sec == $this->retryAfterSeconds) {
                $bar->clear();
                $this->comment("Retrying...");
                $this->tryLoop();
            }
        }
        $bar->finish();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->init();
        $this->checkLastDbMovement();

        if ($this->minutesFromLastUpdate > $this->waitForMinutes) {
            $this->comment($this->minutesFromLastUpdate);
            $this->appDown();
        } else {
            $this->comment("Last DB update " . $this->showTimePassed . " ago (" . $this->lastDbUpdate->update_time . "). Rechecking in " . $this->retryAfterSeconds . " seconds");
            $this->tryLoop();
        }
    }

    public function appDown()
    {
        // \Artisan::call('down');
        $this->info('Application is now in maintenance mode.');
    }
}