<?php 
    $nid = isset($variables["nid"]) ? $variables["nid"] : "";
    $title = isset($variables["title"]) ? $variables["title"] : "";
    $uri = isset($variables["field_content_page_article_image"][0]["uri"]) ? $variables["field_content_page_article_image"][0]["uri"] : "";
    $alt = isset($variables["field_content_page_article_image"][0]["alt"]) ? $variables["field_content_page_article_image"][0]["alt"] : "";
    $vid = isset($variables["field_content_page_article_video"][0]["video_id"]) ? $variables["field_content_page_article_video"][0]["video_id"] : "";
    $caption = isset($variables["field_content_page_article_captn"][0]["value"]) ? trim($variables["field_content_page_article_captn"][0]["value"]) : "";
    $text = isset($variables["field_content_page_article_text"][0]["value"]) ? $variables["field_content_page_article_text"][0]["value"] : "";
    
    $onttl = isset($variables["field_content_page_article_title"][0]["value"]) ? intval($variables["field_content_page_article_title"][0]["value"]) : 0; // 0=nul 1=dip
    $media = isset($variables["field_content_page_article_media"][0]["value"]) ? intval($variables["field_content_page_article_media"][0]["value"]) : 0; // 0=nul 1=img 2=vid
    $dsign = isset($variables["field_content_page_article_dsign"][0]["value"]) ? intval($variables["field_content_page_article_dsign"][0]["value"]) : 0; // 0=nul 1=top 2=lhs 3=rhs
    
    if (isset($content["field_content_page_article_text"])) {
        $content["field_content_page_article_text"]["#label_display"] = "hidden"; // HIDE LABEL
        $renderText = render($content["field_content_page_article_text"]);
    } else {
        $renderText = "";
    }

    if ($onttl != 0) {
        $titleAboveMarkup = "<div class='content_page_article_title content_page_article_title_above'><h3>${title}</h3></div>";
        $titleBelowMarkup = "<h3 class='content_page_article_title content_page_article_title_below'>${title}</h3>";
    } else {
        $titleAboveMarkup = "";
        $titleBelowMarkup = "";
    }
    
    switch($dsign) {
        case 0:  $classMed = "content_page_article_media_nul"; $classEmbed = "content_page_article_notembed"; break;
        case 1:  $classMed = "content_page_article_media_top"; $classEmbed = "content_page_article_notembed"; break;
        case 2:  $classMed = "content_page_article_media_lhs"; $classEmbed = "content_page_article_embedded"; break;
        case 3:  $classMed = "content_page_article_media_rhs"; $classEmbed = "content_page_article_embedded"; break;
        default: $classMed = "content_page_article_media_nul"; $classEmbed = "content_page_article_notembed"; break;
    }
    
    if ($uri != "") {
        $url = image_style_url("content_page_module_article_size",$uri);
        $dbURL = file_create_url($uri);
        $capTxt = ($caption != "") ? "<figcaption>${caption}</figcaption>" : "";
        $imgMarkup = "<div class='${classMed}'><figure><a href='${dbURL}' rel='darkbox'><img class='img-fluid content_page_article_image' src='${url}' alt='${alt}'></a>${capTxt}</figure></div>";
    } else {
        $imgMarkup = "";
    }
    
    if ($vid != "") {
        $capTxt = ($caption != "") ? "<figcaption>${caption}</figcaption>" : "";
        $vidMarkup = "<div class='${classMed}'><figure><div class='content_page_article_video'><iframe width='560' height='349' src='http://www.youtube.com/embed/${vid}?rel=0&hd=1' frameborder='0' allowfullscreen></iframe></div>${capTxt}</figure></div>";
    } else {
        $vidMarkup = "";
    }
    
    
    switch($media) {
        case 0:   $mediaMarkup = "";          break;
        case 1:   $mediaMarkup = $imgMarkup;  break;
        case 2:   $mediaMarkup = $vidMarkup;  break;
        default:  $mediaMarkup = "";          break;
    }
    
    
$layout=<<<OUT

    <div class='content_page_article ${classEmbed}'>
        ${titleAboveMarkup}
            <div class='content_page_article_markup'>
                ${mediaMarkup}
                ${titleBelowMarkup}
                ${renderText}
            </div>
    </div>
    
OUT;

    echo $layout;
    
