<?php
namespace caconnect\extslider;

use Illuminate\Support\ServiceProvider;

class ExtSliderServiceProvider extends ServiceProvider {
    public function boot() {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'extslider');
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
    }

    public function register() {

    }
}


?>