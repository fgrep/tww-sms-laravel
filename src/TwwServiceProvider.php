<?php

namespace NotificationChannels\Tww;

use Illuminate\Support\ServiceProvider;

class TwwServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->app->when(TwwChannel::class)
            ->needs(Tww::class)
            ->give(function () {
                $config = config('services.tww');

                return new Tww(
                    $config['conta'],
                    $config['senha'],
                    isset($config['from'])    ? $config['from']    : null,
                    isset($config['pretend']) ? $config['pretend'] : false
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
