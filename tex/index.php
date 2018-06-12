<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

// Parameters
// Defaults:
$slug = "none";
$category = "all";

// Get params (if they were passed)
if (isset($_GET["t"]) && trim($_GET["t"])){
    $slug = $_GET["t"];
}
if (isset($_GET["c"]) && trim($_GET["c"])){
    $category = $_GET["c"];
}

// Redirect if parameters not received
if (empty($_GET["t"])){
    header("Location: /textures/");
}

$conn = db_conn_read_only();
$info = get_item_from_db($slug, $conn);

// Redirect to search if the texture is not in the DB.
if (sizeof($info) <= 1){
    header("Location: /textures/?s=".$slug);
}

include_start_html($info['name'], $slug);
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');


echo "<div id='item-page'>";
echo "<div id='page-wrapper'>";

echo "<div id='page-data' slug='".$slug."'></div>";

echo "<h1>";
echo "<a href='/textures/?c=all'>";
echo "Textures";
echo "</a>";
echo " >";
if ($category != "all"){
    echo " ";
    echo "<a href='/textures/?c={$category}'>";
    echo nice_name($category, 'category');
    echo "</a>";
    echo " >";
}
echo "<br><b>{$info['name']}</b></h1>";

$is_published = is_in_the_past($info['date_published']) || $GLOBALS['WORKING_LOCALLY'];
if ($is_published){
    echo "<div id='preview-download'>";
    echo "<div id='item-preview'>";
    echo "<img src=\"/files/tex_images/spheres/".$slug.".jpg\" />";
    echo "<img src=\"/files/tex_images/map_previews/".$slug."/albedo.jpg\" id='map-preview-img' class='hide'/>";
    echo "</div>";  // #item-preview

    echo "<div class='download-buttons'>";
    echo "<h2>Download:</h2>";
    $downloads = [];
    $base_dir = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "textures", $slug);
    $files = scandir($base_dir);
    $resolutions = [];
    foreach ($files as $f){
        if (!str_contains($f, '.')){  // Only get resolution folders, not files. is_dir doesn't work reliably on windows, so we assume all folders do not contain '.'
            array_push($resolutions, $f);
        }
    }
    foreach ($resolutions as $r){
        $res_dir = join_paths($base_dir, $r);
        $files = scandir($res_dir);
        $all_maps_f = $slug.'_'.$r.".zip";  // TODO multiple ext zips
        $downloads["all"][$r]["zip"] = $all_maps_f;
        foreach ($files as $f){
            if ($f != '.' and $f != '..' and str_contains($f, '.')){
                if ($f != $all_maps_f){
                    $without_ext = pathinfo($f, PATHINFO_FILENAME);
                    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                    if ($ext != 'zip' and $ext != "png"){
                        $map_type = substr($without_ext, strlen($slug)+1, strlen($r)*-1-1);
                        $downloads[$map_type][$r][$ext] = $f;
                    }
                }
            }
        }
    }
    foreach (array_keys($downloads) as $map_type){
        $map_type_str = nice_name($map_type);
        $map_name_arr = [
            "all" => "<b>All Maps</b>",
            "alb" => "Albedo",
            "diff" => "Diffuse",
            "ao" => "AO",
            "disp" => "Displacement",
            "nor" => "Normal",
            "rough" => "Roughness",
            "spec" => "Specular",
        ];
        foreach (array_keys($map_name_arr) as $m){
            if ($map_type == $m){
                $map_type_str = $map_name_arr[$m];
            }
        }
        
        echo "<div class='map-type'>";
        echo "<div class='map-preview";
        if ($map_type == "all"){
            echo " map-preview-active' id='map-preview-allmaps";
        }
        echo "' map='".$map_type."'><p>";
        echo "<img src='/files/site_images/icons/eye.svg' class='map-preview-icon'>";
        echo "</p></div>";
        echo "<div class='map-download'><p>";
        echo "<img src='/files/site_images/icons/download_white.svg'>";
        echo $map_type_str;
        echo "</p></div>";
        echo "<div class='res-menu hide'>";
        foreach(array_keys($downloads[$map_type]) as $res){
            echo "<div class='res-item'>";
            $i = 0;
            foreach(array_keys($downloads[$map_type][$res]) as $ext){
                $i += 1;
                $file = $downloads[$map_type][$res][$ext];
                $filesize = filesize(join_paths($base_dir, $res, $downloads[$map_type][$res][$ext]))/1024/1024;  // size in MB
                if ($filesize > 10){
                    $d = 0;
                }else if ($filesize > 1){
                    $d = 1;
                }else{
                    $d = 2;
                }
                $filesize = round($filesize, $d);
                echo "<div class='dl-btn'";
                $width = 100/sizeof($downloads[$map_type][$res]);
                echo " style='width: calc(".$width."% - 2em";
                if ($i > 1){
                    echo " - 1px";
                }
                echo ")'";
                echo ">";
                if ($i == 1){
                    echo $res." &sdot; ";
                }
                echo strtoupper($ext);
                echo " &sdot; ".$filesize." MB";
                echo "</div>";
            }
            echo "</div>";  // .res-item
        }
        echo "</div>";  // .res-menu
        echo "</div>";  // .map-type
    }
    echo "<p style='margin: 0.5em; text-align: center;'>License: <a href='http://localhaven:81/p/license.php'>CC0</a><p>";
    echo "</div>";  // .download-buttons
    echo "</div>";  // #preview-download
}


if ($is_published){
    if ($GLOBALS['WORKING_LOCALLY'] && is_in_the_past($info['date_published']) == False){
        echo "<p style='text-align:center;opacity:0.5;'>(working locally on a yet-to-be-published texture)</p>";
    }
    echo "<div id='item-info'>";

    echo "<ul class='item-info-list'>";

    echo "<li>";
    echo "<b>Author:</b> <a href=\"/textures/?s=".to_slug($info['author'])."\">".$info['author']."</a>";
    echo "</li>";

    echo "<li>";
    echo "<b>Published:</b> ".date("d F Y", strtotime($info['date_published']))." (".time_ago($info['date_published']).")";
    echo "</li>";
    
    $downloads_per_day = round($info['download_count']/((time() - strtotime($info['date_published']))/86400));
    echo "<li title=\" (".$downloads_per_day." per day)\">";
    echo "<b>Downloads:</b> ".$info['download_count'];
    echo "</li>";

    echo "<br><li>";
    $category_str = "";
    $category_arr = explode(';', $info['categories']);
    sort($category_arr);
    foreach ($category_arr as $category) {
        $category_str .= '<a href="/textures/?c='.$category.'">'.$category.'</a>, ';
    }
    $category_str = substr($category_str, 0, -2);  // Remove ", " at end
    echo "<b>Categories:</b> {$category_str}";
    echo "</li>";

    echo "<li>";
    $tag_str = "";
    $tag_arr = explode(';', $info['tags']);
    sort($tag_arr);
    if ($info['seamless']){
        array_push($tag_arr, "seamless");
    }
    foreach ($tag_arr as $tag) {
        $tag_str .= '<a href="/textures/?s='.$tag.'">'.$tag.'</a>, ';
    }
    $tag_str = substr($tag_str, 0, -2);  // Remove ", " at end
    echo "<b>Tags:</b> {$tag_str}";
    echo "</li>";
    echo "</ul>";

    echo "</div>";  // .item-info

}else{
    echo "<h1 class='coming-soon'>Coming soon :)</h1>";
}

/*
TODO:
    Similar Textures
    User renders
*/

if (!$GLOBALS['WORKING_LOCALLY']){
    echo "<hr class='disqus' />";
    include_disqus('tex_'.$slug);
}

echo "</div>";  // #page-wrapper
echo "</div>";  // #item-page
?>


<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
