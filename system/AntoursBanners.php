<?php

class AntoursBanners {
    private $resources;
    private $bannerID;
    private $itemClass;
    private $captionCss;
    private $imgCss;

    function __construct($resources, $id = false, $imgCss = array(), $itemCss = array(), $captionCss = array()) {
        $defaultID = "at_banner_" . time();
        $this->resources = $resources;
        $this->bannerID = $id ? $id : $defaultID;
        $this->itemCss = $itemCss;
        $this->captionCss = $captionCss;
        $this->imgCss = $imgCss;
    }

    public function render($indicators = false, $captions = false, $controls = true) {
        $banner = "<div id='$this->bannerID' class='carousel slide' data-ride='carousel'>";

        if (count($this->resources) < 2) {
            $controls = false;
            $indicators = false;
        }

        // render indicators if needed
        if ($indicators) {
            $banner .= $this->renderIndicators();
        }

        // render each image
        $resources = $this->renderBanners($captions);
        // add images to banner html
        $banner .= $resources;

        if ($controls) {
            $banner .= $this->renderControls();
        }

        $banner .= "</div>";

        echo $banner;
    }

    private function renderIndicators() {
        $index = 0;
        $id = $this->bannerID;
        $indicators = "<ol class='carousel-indicators'>";

        foreach($this->resources as $key => $resource) {
            $active = $index === 0 ? 'active' : '';
            $indicator = "<li data-target='#$id' data-slide-to='$index' class='$active'></li>";
            $indicators .= $indicator;
            $index++;
        }

        $indicators .= "</ol>";

        return $indicators;
    }

    private function renderBanners($captions) {
        $index = 0;
        $banners = array("<div class='carousel-inner' role='listbox'>");
        
        foreach($this->resources as $picture) {
            $active = $index === 0 ? 'active' : '';

            $item = "<div class='item $active'>";
            $item .= $picture;
            $item .= "</div>";

            array_push($banners, $item);

            $index++;
        }

        array_push($banners, "</div>");

        $banners = implode("", $banners);

        return $banners;
    }

    private function renderControls() {
        $controls = '
            <a class="left carousel-control" href="#'. $this->bannerID .'" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="right carousel-control" href="#'. $this->bannerID .'" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        ';

        return $controls;
    }
}