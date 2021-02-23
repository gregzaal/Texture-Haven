<?php

// Site Variables
$SITE_NAME = "Texture Haven";
$SITE_DESCRIPTION = "100% Free High Quality Textures for Everyone";
$SITE_TAGS = "Texture,PBR,free,cc0,creative commons";
$SITE_DOMAIN = "texturehaven.com";
$SITE_URL = "https://".$SITE_DOMAIN;
$SITE_LOGO = "/core/img/Texture Haven Logo.svg";
$SITE_LOGO_URL = $SITE_URL.$SITE_LOGO;
$META_URL_BASE = $SITE_URL."/files/tex_images/spheres/";
$DEFAULT_AUTHOR = "Rob Tuytel, Greg Zaal";
$CONTENT_TYPE = "textures";  // For DB table name & library url
$CONTENT_TYPE_SHORT = "tex";  // For CSS classes
$CONTENT_TYPE_NAME = "textures";  // For display
$TEX1_CONTENT_TYPE = "tex-pbr";
$TEX1_CONTENT_METHOD = "scanned";
$HANDLE_PATREON = "TextureHaven";
$HANDLE_TWITTER = "texturehaven";
$HANDLE_FB = "texturehaven";

require_once($_SERVER['DOCUMENT_ROOT'].'/core/core.php');


// ============================================================================
// Database functions
// ============================================================================

function make_sort_SQL($sort) {
    // Return the ORDER BY part of an SQL query based on the sort method
    $sql = "ORDER BY id DESC";
    switch ($sort) {
        case "date_published":
            $sql = "ORDER BY date_published DESC, download_count DESC, slug ASC";
            break;
        case "popular":
            $sql = "ORDER BY download_count/POWER(ABS(DATEDIFF(date_published, NOW()))+1, 1.7) DESC, download_count DESC, slug ASC";
            break;
        case "downloads":
            $sql = "ORDER BY download_count DESC, date_published DESC, slug ASC";
            break;
        default:
            $sql = "ORDER BY id DESC";
    }
    return $sql;
}

function make_search_SQL($search, $category="all", $author="all") {
    // Return the WHERE part of an SQL query based on the search

    $only_past = "date_published <= NOW()";
    $sql = "WHERE ".$only_past;

    if ($search != "all"){
        // Match multiple words using AND
        $terms = explode(" ", $search);
        $i = 0;
        $terms_sql = "";
        foreach ($terms as $t){
            $i++;
            $terms_sql .= " AND ";
            $terms_sql .= "(";
            $terms_sql .= "CONCAT(';',tags,';') REGEXP '[; ]".$t."[; ]'";
            $terms_sql .= " OR ";
            $terms_sql .= "CONCAT(';',categories,';') REGEXP '[; ]".$t."[; ]'";
            $terms_sql .= " OR ";
            $terms_sql .= "name LIKE '%".$t."%'";
            $terms_sql .= ")";
        }
        $sql .= $terms_sql;
    }

    if ($category != "all"){
        $sql .= " AND (categories LIKE '%".$category."%')";
    }

    if ($author != "all"){
        $sql .= " AND (author LIKE '".$author."')";
    }

    return $sql;
}


// ============================================================================
// Texture Grid
// ============================================================================

function make_grid_item($i, $category="all"){
    $html = "";

    $slug = $i['slug'];
    $html .= "<a href=\"/tex/?";
    if ($category != "all"){
        $html .= "c=".$category."&amp;";
    }
    $html .= "t=".$slug;
    $html .= "\">";
    $html .= "<div class='grid-item'>";

    $html .= "<div class='thumbnail-wrapper'>";

    // Encoded tiny proxy images so that there is *something* to look at while the images load
    $html .= "<img ";
    $html .= "class='thumbnail-proxy' ";
    $local_file = get_slug_thumbnail($slug, 48, 40);
    $proxy_data = base64_encode(file_get_contents($local_file));
    $html .= "src=\"data:image/jpeg;base64,".$proxy_data."\" ";
    $html .= "/>";

    // Main thumbnail images that are only loaded when they come into view
    $html .= "<img ";
    $html .= "class='thumbnail' ";
    $local_file = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "tex_images", "thumbnails", '_dummy_48.png');
    $dummy_data = base64_encode(file_get_contents($local_file));
    $html .= "src=\"data:image/png;base64,".$dummy_data."\" ";
    $img = get_slug_thumbnail($slug, 350, 90);
    $img = filepath_to_url($img);
    $html .= "data-src=\"{$img}\" ";
    $html .= "alt=\"Texture: {$i['name']}\" ";
    $html .= "/>";

    $age = time() - strtotime($i['date_published']);
    if ($age < 7*86400){
        // Show "New!" in right corner if item is less than 7 days old
        $html .= '<div class="new-triangle"></div>';
        $html .= '<div class="new">New!</div>';
    }

    $html .= "</div>";  //.thumbnail-wrapper

    $html .= "<div class='description-wrapper'>";
    $html .= "<div class='description'>";

    $html .= "<div class='title-line'>";
    $html .= "<h3>".$i['name']."</h3>";
    $html .= "</div>";

    $html .= "<p class='age'>".time_ago($i['date_published'])."</p>";

    $html .= "</div>";  // description

    $html .= "</div>";  // description-wrapper

    $html .= "</div>";  // grid-item
    $html .= "</a>";

    return $html;
}


?>
