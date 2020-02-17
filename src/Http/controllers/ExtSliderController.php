<?php
namespace caconnect\extslider\Http\controllers;
use App\Http\Controllers\Controller;
use caconnect\extslider\Models\Extslider;
use Illuminate\Support\Facades\Storage;

class ExtSliderController extends Controller {

    private $feed, $alias, $group, $scripts, $css, $html;

    function __construct()
    {
        $this->feed = config('settings.feed_url');
    }

    protected function loadXml() {
        try {
            return simplexml_load_file($this->feed, 'SimpleXMLElement', LIBXML_NOCDATA);
        } catch (\Exception $e) {
            die('The provided format is not valid xml');
        }
    }




    public function index() {
        $loadXml = $this->loadXml();
        $changeReturnFormat = json_decode(json_encode($loadXml), 1);

        $this->alias = $changeReturnFormat['slider']['alias'];
        $this->group = $changeReturnFormat['slider']['group'];
        $this->css = '';
        $this->html = '';
        $this->scripts = '';

        dd($loadXml);
    }

    public function getAlias() {
        return $this->alias;
    }

    public function getGroup() {
        return $this->group;
    }

    public function update() {

//        $xml=('http://sliders.caconnect.ro/index.php?c=admin&m=ajax&action=revslider_ajax_action&client_action=preview_slider&only_markup=true&dummy=false&nonce=&sliderid=2&api=true');
//        $xml = simplexml_load_file($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
//        $xmlJson = json_encode($xml);
        $newData = json_decode(json_encode($xmlJson), 1);
        $sliderHtmlOutput = $newData['slider']['resources']['item'][0];
        $sliderScriptsOutput = $newData['slider']['resources']['item'][1];

        function prepareData($sliderHtml, $toSearch = [], $slider = false) {
            if(isset($toSearch) && count($toSearch)) {
                foreach ($toSearch[1] as $k => $src) {
                    $original_links = $src;
                    if($slider['id']) {
                        $contents = file_get_contents($src);
                        $name = substr($src, strrpos($src, '/') + 1);
                        $newAssetsPath = $slider['id'] . "/"/*.$slider['alias']."/"*/;
                        Storage::disk('public')->put('sliders/' . $newAssetsPath . $name, $contents);
                        $fileName = last(explode('/', $src));

                        $fileNameArray = \Storage::disk('public')->url('sliders/' . $newAssetsPath . $fileName);
                        $sliderHtml = str_replace($original_links, $fileNameArray, $sliderHtml);
                    } else {
                        $fileNameArray = \Storage::disk('public')->url('sliders/assets/');
                        $sliderHtml =  str_ireplace($original_links, $fileNameArray, $sliderHtml);

                    }
                }
                return $sliderHtml;
            }
        }

        $scriptMatches = array();
        preg_match_all('/jsFileLocation:"(.*?)\"/s', $sliderScriptsOutput, $scriptMatches);

        $matches = array();
        preg_match_all('/src=\"(.*?)\\" / s', $sliderHtmlOutput, $matches);

        $slider['slug'] = $newData['slider']['group'];
        $slider['alias'] = $newData['slider']['alias'];;
        $slider['scripts'] = (prepareData($sliderScriptsOutput, $scriptMatches));
        $slider['slider'] = (prepareData($sliderHtmlOutput, $matches, $newData['slider']));
        $slider['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $slider['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();

        $insertion = Sliders::updateOrInsert(['slug' => $newData['slider']['group'], 'alias' => $newData['slider']['alias']], $slider);

        if($insertion) {
            print_r("Slider has been updated");
            die;
        }

    }

}

?>