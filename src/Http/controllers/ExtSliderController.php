<?php
namespace caconnect\extslider\Http\controllers;
use App\Http\Controllers\Controller;
use caconnect\extslider\Models\Extslider;
use Illuminate\Support\Facades\Storage;

class ExtSliderController {

    private $feed, $alias, $group, $scripts, $css, $html, $sliderid, $formattedFeed, $identifier;

    function __construct($identifier, $updateDB = false)
    {
        $this->identifier = $identifier;
        $this->feed = config('settings.feed_url');
        $this->init();
        if($updateDB)
            $this->updateTable();
    }

    private function init() {
        $this->getSlider();
        $this->setSliderid();
        $this->setAlias();
        $this->setGroup();
        $this->setScripts();
        $this->setHtml();
    }

    protected function loadXml() {
        try {
            return simplexml_load_file($this->feed, 'SimpleXMLElement', LIBXML_NOCDATA);
        } catch (\Exception $e) {
            die('The provided format is not valid xml');
        }
    }
    private function getSlider() {
        switch(gettype($this->identifier)) {
            case 'integer':
                $param = 'id';
                break;
            case 'string':
                $param = 'group';
                break;
        }
        $this->formattedFeed = json_decode(json_encode($this->loadXml()->xpath('//slider['.$param.'="'.$this->identifier.'"]')[0]), 1);
    }
    private function setAlias() {
        $this->alias = $this->formattedFeed['alias'];
    }
    private function setSliderid() {
        $this->sliderid= $this->formattedFeed['id'];
    }
    private function setGroup() {
        $this->group = $this->formattedFeed['group'];
    }
    private function setCss() {
//        $this->css = $this->formattedFeed['css'];
    }
    private function setScripts() {
        $fileNameArray = \Storage::disk('public')->url('sliders/'.$this->sliderid.'/js/');
        preg_match_all('/jsFileLocation:"(.*?)\"/s', $this->formattedFeed['resources']['item'][1], $scriptMatches);
        $this->scripts = str_replace($scriptMatches[1][0], $fileNameArray, $this->formattedFeed['resources']['item'][1]);
    }
    private function setHtml() {
        preg_match_all('/src=\"(.*?)\\" / s', $this->formattedFeed['resources']['item'][0], $matches);
        $this->html = ($this->parseLinks($this->formattedFeed['resources']['item'][0], $matches, $this->sliderid));
    }

    private function parseLinks($content, $search = [], $id = false) {
        $original_links = $search[1];
        foreach($original_links as $original_link) {
            $name = substr($original_link, strrpos($original_link, '/') + 1);
            $names[] = $name;
            try {
                Storage::disk('public')->put('sliders/' . $id . '/media/' . $name, $original_link);
            } catch (\Exception $e) {
                print('Could not pull external media on the server');
                die;
            }
            $fileNameArray = \Storage::disk('public')->url('sliders/' . $id . '/media/' . $name);
            $content = str_replace($original_link, $fileNameArray, $content);
        }
        return $content;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function getGroup() {
        return $this->group;
    }

    public function getCSS() {
        return $this->css;
    }

    public function getScripts() {
        return $this->scripts;
    }

    public function getHtml() {
        return $this->html;
    }

    private function updateTable() {

        $slider['slug'] = $this->group;
        $slider['alias'] = $this->alias;
        $slider['css_content'] = $this->css;
        $slider['html_content'] = $this->html;
        $slider['scripts_content'] = $this->scripts;
        $slider['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $slider['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();
//        $slider['target_id'] = null;

        $insertion = Extslider::updateOrInsert(['external_id' => $this->sliderid], $slider);

        if($insertion) {
            print_r("Slider has been updated");
            die;
        }

    }

}

?>