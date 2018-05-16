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
    $dsign = isset($variables["field_content_page_article_dsign"][0]["value"]) ? intval($variables["field_content_page_article_dsign"][0]["value"]) : 0; // 0=nul 1=top 2=lhs 3=rhs 4=dot 5=ckE 6=ckO
    
    if (isset($content["field_content_page_article_text"])) {
        $content["field_content_page_article_text"]["#label_display"] = "hidden"; // HIDE LABEL
        $renderText = "<div tabindex='0' class='content_page_article_rendered_text'>" . render($content["field_content_page_article_text"]). "</div>";
    } else {
        $renderText = "";
    }

    $readerText = isset($content["field_content_page_screen_reader"]["#items"][0]["value"]) ? "<div tabindex='0' class='content_page_article_screen_reader'>" . $content["field_content_page_screen_reader"]["#items"][0]["value"] . "</div>" : "";

    switch($dsign) {
        case 0:  $classMed = "content_page_article_media_nul"; $classEmbed = "content_page_article_notembed"; break;
        case 1:  $classMed = "content_page_article_media_top"; $classEmbed = "content_page_article_notembed"; break;
        case 2:  $classMed = "content_page_article_media_lhs"; $classEmbed = "content_page_article_embedded"; break;
        case 3:  $classMed = "content_page_article_media_rhs"; $classEmbed = "content_page_article_embedded"; break;
        case 4:  $classMed = "content_page_article_media_dot"; $classEmbed = "content_page_article_bltdlist"; break;
        case 5:  $classMed = "content_page_article_media_ess"; $classEmbed = "content_page_article_checklst"; $titleTxt = ($caption == "") ? 'This item is required for most students.' : strip_tags(str_replace("'","",$caption)); break;
        case 6:  $classMed = "content_page_article_media_opt"; $classEmbed = "content_page_article_checklst"; $titleTxt = ($caption == "") ? 'This item is recommended.'                : strip_tags(str_replace("'","",$caption)); break;
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
        $vidMarkup = "<div class='${classMed}'><figure><div class='content_page_article_video'><iframe width='560' height='349' src='//www.youtube.com/embed/${vid}?autohide=1&showinfo=0&rel=0&hd=1' frameborder='0' allowfullscreen></iframe></div>${capTxt}</figure></div>";
    } else {
        $vidMarkup = "";
    }
    
    
    switch($media) {
        case 0:   $mediaMarkup = "";            $titleClass="noMedia";          break;
        case 1:   $mediaMarkup = $imgMarkup;    $titleClass="isMedia-${dsign}"; break;
        case 2:   $mediaMarkup = $vidMarkup;    $titleClass="isMedia-${dsign}"; break;
        default:  $mediaMarkup = "";            $titleClass="noMedia";          break;
    }
    
    if ($dsign == 4) { // DOT or BULLET Layout
        if ($uri != "") {
            $url = image_style_url("content_page_module_size_tile",$uri);
            $mediaMarkup = "<div class='${classMed}'><img class='img-fluid content_page_article_image_bullet' src='${url}' alt='${alt}'></div>";
        } else {
            $mediaMarkup = "";
        }
    }

    if ($dsign == 5)  { // Essential Checklist
        $mediaMarkup = "<div class='${classMed}'><i class='fa fa-check' aria-hidden='true' title='${titleTxt}'></i></div>";
    }
    if ($dsign == 6)  { // Optional Checklist
        $mediaMarkup = "<div class='${classMed}'><i class='fa fa-info' aria-hidden='true' title='${titleTxt}'></i></div>";
    }

    if ($onttl != 0) {
        $titleMarkup = "<div class='content_page_article_title'><h3 tabindex='0' class='${titleClass}'>${title}</h3></div>";
    } else {
        $titleMarkup = "";
    }


$layNOMedia=<<<OUT
	<a name='article-${nid}'></a>
    <div id='article-${nid}' class='content_page_article ${classEmbed}'>
        ${titleMarkup}
        <div class='content_page_article_markup'>
            ${renderText}
        </div>
    </div>
    ${readerText}
OUT;

$layUPMedia=<<<OUT
	<a name='article-${nid}'></a>
    <div id='article-${nid}' class='content_page_article ${classEmbed}'>
        ${titleMarkup}
        <div class='content_page_article_markup'>
            ${mediaMarkup}
            ${renderText}
        </div>
    </div>
    ${readerText}
OUT;

$laySDMedia=<<<OUT
	<a name='article-${nid}'></a>
    <div id='article-${nid}' class='content_page_article ${classEmbed}'>
        ${titleMarkup}
            <div class='content_page_article_markup'>
                ${mediaMarkup}
                ${renderText}
            </div>
    </div>
    ${readerText}
OUT;

$layDTMedia=<<<OUT
    <a name='article-${nid}'></a>
    <div id='article-${nid}' class='content_page_article ${classEmbed}'>
        <div class='content_page_article_bullet_image'>${mediaMarkup}</div>
        <div class='content_page_article_markup_bullet'>
            ${titleMarkup}
            ${renderText}
        </div>
    </div>
    ${readerText}
OUT;

$layCKMedia=<<<OUT
    <a name='article-${nid}'></a>
    <div id='article-${nid}' class='content_page_article ${classEmbed}'>
        <div class='content_page_article_checklist_image'>${mediaMarkup}</div>
        <div class='content_page_article_markup_checklist'>
            ${titleMarkup}
            ${renderText}
        </div>
    </div>
    ${readerText}
OUT;

    switch($dsign) {
        case 1:  echo $layUPMedia; break; // TOP
        case 2:  echo $laySDMedia; break; // LHS
        case 3:  echo $laySDMedia; break; // RHS
        case 4:  echo $layDTMedia; break; // DOT
        case 5:  echo $layCKMedia; break; // CHK
        case 6:  echo $layCKMedia; break; // CHK
        default: echo $layNOMedia; break; // NO MEDIA
    }
    
