<?php
namespace caconnect\extslider\Http\controllers;
use App\Http\Controllers\Controller;
use caconnect\extslider\Models\Extslider;

class ExtSliderController extends Controller {
    public function index() {
        dd(Extslider::all());
        echo 'Good job, slider loaded';
    }
}

?>