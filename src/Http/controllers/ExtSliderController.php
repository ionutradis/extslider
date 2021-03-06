<?php
namespace ionutradis\extslider\Http\controllers;
use App\Http\Controllers\Controller;
use ionutradis\extslider\Models\Extslider;
use Illuminate\Support\Facades\Storage;

class ExtSliderController {

    private $feed, $alias, $group, $scripts, $css, $html, $sliderid, $formattedFeed, $identifier, $status, $updateDB, $access, $mode;

    function __construct($identifier = false, $updateDB = false)
    {
        $this->updateDB = $updateDB;
        $this->identifier = $identifier;
        $this->feed = config('extslider.feed_url');
        $this->init();
    }

    private function init() {
        $this->getSlider();
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
            case 'boolean':
                $param = 'all';
                break;
        }
        if($param !== 'all') {
            if(isset($this->loadXml()->xpath('//slider['.$param.'="'.$this->identifier.'"]')[0])) {
                $this->formattedFeed = json_decode(json_encode($this->loadXml()->xpath('//slider['.$param.'="'.$this->identifier.'"]')[0]), 1);
                $this->setContent();
                if($this->updateDB)
                    $this->updateTable();
            } else {
                print('Error, no slider found');
                die;
            }
        } else {

            $content = json_decode(json_encode($this->loadXml()),1)['slider'];
            if(!isset($content['id'])) {
                foreach($content as $slider) {
                    $this->formattedFeed = $slider;
                    $this->setContent();
                    if($this->updateDB)
                        $this->updateTable();
                }
            } else {
                $this->formattedFeed = $content;
                $this->setContent();
                if($this->updateDB)
                    $this->updateTable();
            }
        }
    }

    private function setContent() {
        $this->setSliderid();
        $this->setAlias();
        $this->setStatus();
        $this->setAccess();
        $this->setMode();
        $this->setStatus();
        $this->setGroup();
        $this->setScripts();
        $this->setCss();
        $this->setHtml();
    }

    private function setAlias() {
        $this->alias = $this->formattedFeed['alias'];
    }
    private function setMode() {
        $this->mode = $this->formattedFeed['mode'];
    }
    private function setAccess() {
        $this->access = $this->formattedFeed['access_allow'];
    }
    private function setSliderid() {
        $this->sliderid= $this->formattedFeed['id'];
    }
    private function setStatus() {
        $this->status= $this->formattedFeed['status'];
    }
    private function setGroup() {
        $this->group = $this->formattedFeed['group'];
    }
    private function setCss() {
        $this->css = isset($this->formattedFeed['resources']['item'][3]) ? $this->formattedFeed['resources']['item'][3] : '';
    }
    private function setScripts() {
        preg_match_all('/src="([^"]+)/i', $this->formattedFeed['resources']['item'][2], $matches);
        if(isset($this->formattedFeed['resources']['item'][2])) {
            $this->scripts .= $this->parseLinks($this->formattedFeed['resources']['item'][2], $matches);
        }

        $fileNameArray = \Storage::disk('public')->url('sliders/'.md5($this->sliderid.$this->alias).'/js/');
        preg_match_all('/jsFileLocation:"(.*?)\"/s', $this->formattedFeed['resources']['item'][1], $scriptMatches);
        $this->scripts .= str_replace($scriptMatches[1][0], $fileNameArray, $this->formattedFeed['resources']['item'][1]);
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
                $path = 'sliders/';
                if($id){
                    $path .= md5($id.$this->alias) . '/media/';
                } else {
                    $path .= 'assets/';
                }
                Storage::disk('public')->put($path . $name, file_get_contents($original_link));
            } catch (\Exception $e) {
                print('Could not pull external media on the server');
                die;
            }
            $fileNameArray = \Storage::disk('public')->url($path . $name);
            $content = str_replace($original_link, $fileNameArray, $content);
        }
        return $content;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getMode() {
        return $this->mode;
    }

    public function getAccess() {
        return $this->access;
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
        $slider['status'] = $this->status;
        $slider['css_content'] = $this->css;
        $slider['html_content'] = $this->html;
        $slider['mode'] = $this->mode;
        $slider['ips'] = $this->access;
        $slider['scripts_content'] = $this->scripts;
        $slider['created_at'] = \Carbon\Carbon::now()->toDateTimeString();
        $slider['updated_at'] = \Carbon\Carbon::now()->toDateTimeString();
//        $slider['target_id'] = null;

        $insertion = Extslider::updateOrInsert(['external_id' => $this->sliderid], $slider);

        if($insertion) {
            print_r("Slider $this->alias - $this->group has been updated.");
        }

    }

}

?>