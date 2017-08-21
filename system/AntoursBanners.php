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
        $banners = "<div class='carousel-inner' role='listbox'>";
        $itemCss = implode(" ", $this->itemCss);
        $captionCss = implode(" ", $this->captionCss);
        $imgCss = implode(" ", $this->imgCss);
        
        foreach($this->resources as $id => $resource) {
            $title = $resource['title'];
            $caption = $resource['caption'];
            $alt = $resource['alt'];
            $desc = $resource['description'];
            $active = $index === 0 ? 'active' : '';
            $url = $resource['full_url'];

            $item = "<div class='item $active $itemCss'>";
            $item .= "<img src='$url' title='$title' alt='$alt' class='$imgCss' />";

            if ($captions) {
                $item .= "<div class='carousel-caption $captionCss'>$desc</div>";
            }

            $item .= "</div>";

            $banners .= $item;

            $index++;
        }

        $banners .= "</div>";

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