# SETUP

* INSTALLATION

        composer require ionutradis/extslider:1.1.x-dev
        
* LOAD PROVIDER IN YOUR config/app.php

        ionutradis\extslider\ExtSliderServiceProvider::class,

* PUBLISH CONFIG FILE

        php artisan vendor:publish --provider="ionutradis\extslider\ExtSliderServiceProvider" --tag=config

* SET FEED URL OF XML FORMAT IN config/extslider.php


* RUN COMMAND
       
        php artisan migrate
        
# USAGE
        
* IMPORT/UPDATE SLIDERS

        new ExtSliderController('slug'); //string type updates local slider by slider group
                        or
        new ExtSliderController(1); // int type fetches and stores local slider by external slider id
                        or
        new ExtSliderController(true); // bool type tells the script to fetch all the sliders
                        or
        new ExtSliderController(1, true); // by passing 'true' as second parameter, the script will also update the slider(s)
        
* VIEW

        @include('extslider::html', ['slug' => 'homepage'])