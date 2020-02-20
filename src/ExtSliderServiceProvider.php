<?php
namespace ionutradis\extslider;

use Illuminate\Support\ServiceProvider;

class ExtSliderServiceProvider extends ServiceProvider {
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'extslider');
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');


        $this->publishes([
            __DIR__.'/config/settings.php' => config_path('config/settings.php'),
        ], 'config');
    }

    public function register() {
        $this->mergeConfigFrom( __DIR__.'/config/settings.php', 'settings');
    }
}


?>