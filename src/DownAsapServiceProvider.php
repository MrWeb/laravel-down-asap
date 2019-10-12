<?php
namespace Mrweb\DownAsap;

use Illuminate\Support\ServiceProvider;
use Mrweb\DownAsap\Commands\DownAsapCommand;

class DownAsapServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DownAsapCommand::class,
            ]);
        }
    }
}
