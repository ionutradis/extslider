# SETUP

* INSTALLATION

        composer require ionutradis/extslider:1.0.x-dev
        
* LOAD PROVIDER IN YOUR config/app.php

        ionutradis\extslider\ExtSliderServiceProvider::class,

* PUBLISH CONFIG FILE

        php artisan vendor:publish --provider="ionutradis\extslider\ExtSliderServiceProvider" --tag=config

* SET FEED URL OF XML FORMAT IN config/extslider.php


* RUN COMMAND
       
        php artisan migrate
        
# USAGE
        
* IMPORT/UPDATE SLIDERS

        new ExtSliderController('slug', true);
        
* VIEW

        @include('extslider::html', ['slug' => 'homepage'])