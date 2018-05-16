<?php 
    $nid = isset($variables["nid"]) ? $variables["nid"] : "";
    $title = isset($variables["title"]) ? $variables["title"] : "";
    $uri = isset($variables["field_content_page_image"][0]["uri"]) ? $variables["field_content_page_image"][0]["uri"] : "";
    $alt = isset($variables["field_content_page_image"][0]["alt"]) ? $variables["field_content_page_image"][0]["alt"] : "";
    $caption = isset($variables["field_content_page_image_caption"][0]["value"]) ? trim($variables["field_content_page_image_caption"][0]["value"]) : "";
    $text = isset($variables["field_content_page_text"][0]["value"]) ? trim($variables["field_content_page_text"][0]["value"]) : "";
    $summary = isset($variables["field_content_page_text"][0]["summary"]) ? $variables["field_content_page_text"][0]["summary"] : "";

	$layout = intval(variable_get('content_page_module_PAGE_LAYOUT', '0'));
    
    if ($text != "") {
        $content["field_content_page_text"]["#label_display"] = "hidden"; // HIDE LABEL
        if ($layout == 0) {
            $renderText = "<div class='content_page_page'><div tabindex='0' class='content_page_text'>" . render($content["field_content_page_text"]) . "</div></div>";
        } else {
            $renderText = "<div tabindex='0' class='content_page_text'>" . render($content["field_content_page_text"]) . "</div>";
        }
    } else {
        $renderText = "";
    }
    
    if (isset($content["field_content_page_articles"])) {
        $renderArticles = "<div class='content_page_articles'>" . render($content["field_content_page_articles"]) . "</div>";
    } else {
        $renderArticles = "";
    }
    
    if ($uri != "") {
        $url = image_style_url("content_page_module_size",$uri);
        $capTxt = ($caption != "") ? "<figcaption>${caption}</figcaption>" : "";
        $imgMarkup = "<figure class='content_page_figure'><img class='img-fluid content_page_image' src='${url}' alt='${alt}'>${capTxt}</figure>";
        
        $url = image_style_url("content_page_module_size_wide",$uri);
        $imgWideMarkup = "<div class='content_page_hero_break'><img class='content_page_image_wide' src='${url}'></div>";
        
    } else {
        $imgMarkup = "";
        $imgWideMarkup = "";
    }

    // ========================== GET MENU LINKED PAGES ==========================
    $linkMarkup = "";
    $json = variable_get("content_page_module_MENU_TREE", "{}");
    $menuArray = @json_decode($json,true);
    if (json_last_error() == JSON_ERROR_NONE) {
        $mlid = -1;
        foreach($menuArray as $item) {
            if ($item["nid"] == $nid) {
                if ($mlid == -1) {
                    $mlid = $item["mlid"];
                } else if ($mlid > $item["mlid"]) {
                    $mlid = $item["mlid"];
                }
            }
        }
        $linkList = "<h3 class='content_page_related_links_title'>Related Content</h3><ul class='content_page_related_links'>";
        $linkCnt = 0;
        foreach($menuArray as $item) {
            if ($item["plid"] == $mlid) {
                $link_title = isset($item["link_title"]) ? trim($item["link_title"]) : "";
                $normal_path = isset($item["normal_path"]) ? trim($item["normal_path"]) : "";
                $external = isset($item["external"]) ? intval($item["external"]) : 0;
                $link_path = ($external == 0) ? url($normal_path, array('alias'=>false,'absolute'=>true)) : $normal_path;
                $target = ($external != 0) ? "target='_blank'" : "";
                $linkList.= "<li><a href='${link_path}' ${target}>${link_title}</a></li>";
                ++$linkCnt;
            }
        }
        $linkList.= "</ul>";
        if ($linkCnt > 0) {
            $linkMarkup = "<div tabindex='0' class='content_page_linked_pages'>${linkList}</div>";
        }
    }

    
$layout0=<<<OUT
    <div class="content_page nid-${nid}">
        <div class="content_page_simple">
            <div class="content_page_title"><h1 tabindex="0">${title}</h1></div>
            ${imgMarkup}
            ${renderText}
            ${renderArticles}
            ${linkMarkup}
        </div>
     </div>
    <script>
        jQuery(document).ready(function($) {
            $('a[rel=darkbox]').darkbox();
        }(jQuery)); 
    </script>
OUT;
    
$layout1=<<<OUT
    <div class="content_page nid-${nid}">
        <div class="content_page_background_column">
            <div class="content_page_background_white">
                <div class="content_page_page">
                    <div class="content_page_title"><h1 tabindex="0">${title}</h1></div>
                    ${renderText}
                </div>
                ${renderArticles}
                ${linkMarkup}
            </div>
        </div>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('a[rel=darkbox]').darkbox();
            $('.type-content-page').css({"position":"relative"});
            $('.type-content-page').append("${imgWideMarkup}");
        }(jQuery)); 
    </script>
OUT;
    
    switch($layout) {
        case 0:  echo $layout0; break;
        case 1:  echo $layout1; break;
        default:  echo $layout0; break;
    }

  

   
   