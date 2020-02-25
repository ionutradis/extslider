<?php
namespace ionutradis\extslider\Tests;
use App\Http\Controllers\Controller;
use ionutradis\extslider\Models\Extslider;

class Slider {

    function __construct()
    {
    }

    public function index($identifier) {
        $checkDB = Extslider::where('slug', $identifier)->where('mode', 'testing');
        if($checkDB->get()->count() > 0) {
            if($checkDB->first()->ips !== 'all') {
                $explodeIps = explode(',', $checkDB->first()->ips);
                if(!in_array(\request()->ip(), $explodeIps)) {
                    print_r('You have no acces to view this slider');
                    die;
                }
            }
            return view('extslider::test', ['slug' => $checkDB->first()->slug]);
        } else {
            print_r('Identifier <b>'.$identifier.'</b> not found');
        }
    }
}

?>