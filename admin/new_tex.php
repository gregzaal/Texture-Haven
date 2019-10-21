<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Texture</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="new_tex.js"></script>
</head>
<body>

<?php include ($_SERVER['DOCUMENT_ROOT'].'/admin/header.php'); ?>
<div id="page-wrapper">
<div id="page">

<form action="/admin/new_tex_submit.php" method="POST" enctype="multipart/form-data" id="new-tex-form">

    <?php
    if(isset($_GET["error"])) {
    echo "<div class=\"form-item error\">";
        echo "<h2>Error: </h2>";
        echo "<p> ".$_GET["error"]."</p>";
    echo "</div>";
    }
    ?>

    <!-- <div class="form-item">
    <h2>Upload texture maps:</h2>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <input type="file" name="texture_maps[]" multiple="multiple" id="texture-maps" required>
    <div class="tooltip hidden"
        >Select all files for this texture.<br>
        Choose only the highest resolution of each map. Lower resolution versions will be generated.<br>
        <br>
        The names of these files must match this pattern: <q>slug_maptype.png</q><br>
        They must <b>not</b> include the resolution (e.g. not slug_maptype_<b>8k</b>.png).
    </div>
    <ul id="map-list" class="hidden"></ul>
    </div> -->

    <div class="form-item">
    <h2>Upload sphere render:</h2>
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <input type="file" name="sphere_render" id="sphere-render" required>
    <div class="tooltip hidden">Must be a 640x640 PNG with transparent background.</div>
    <div id="sphere-render-preview-wrapper" class="hidden">
        <img src="#" id="sphere-render-preview">
    </div>
    </div>

    <div class="form-item">
    <h2>Name:</h2>
    <input id="form-name" type="text" name="name" value="">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">The name of the Texture, as seen on the site (e.g. <q>Red Brick 02</q>).</div>
    </div>

    <div class="form-item">
    <h2>Slug:</h2>
    <input id="form-slug" type="text" name="slug-visible" value="" disabled>
    <input id="form-slug-actual" type="text" name="slug" value="" hidden>  <!-- Duplicate hidden slug since disabled inputs aren't included in the GET parameters -->
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <label><input id="auto-slug" type="checkbox" name="auto-slug" value="Auto" checked>Auto</label><br>
    <div class="tooltip hidden">Unique identifier used for technical purposes. No punctuation or spaces allowed (e.g. <q>red_brick_02</q>).<br>
    <b>Must match the uploaded files.</b></div>
    </div>
    
    <div class="form-item">
    <h2>Is seamless:</h2>
    <input id="form-seamless" type="checkbox" name="seamless" value="Seamless" checked><br>
    </div>
    
    <div class="form-item">
    <h2>Author:</h2>
    <input id="form-author" type="text" name="author" value="Rob Tuytel">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">The original creator of this texture. Credit is shown on the texture page.</div>
    </div>

    <div class="form-item">
    <h2>Categories:</h2>
    <input id="form-cats" type="text" name="cats" value="">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">The category this texture belongs to, as grouped in the sidebar.<br>Choose several from below, or type new ones into the box.</div>
    <div id="button-list">
    <?php
    echo "<div class='cat-type'>";
    $cats = get_all_categories();
    foreach ($cats as $cat){
        if ($cat){
            if ($cat != 'all'){
                echo "<div class='button cat-option'>";
                echo $cat;
                echo "</div>";
            }
        }
    }
    echo "</div>";
    ?>
    </div>
    </div>

    <div class="form-item">
    <h2>Tags:</h2>
    <input id="form-tags" type="text" name="tags" value="">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">What someone might search for (e.g. <q>old, dirty, red, damaged</q>).<br>Choose several from below, or type new ones into the box.</div>
    <div id="button-list">
    <?php
    echo "<div class='cat-type'>";
    $db = get_from_db("popular", "all", "all", "all", NULL, 0);
    $all_tags = [];
    foreach ($db as $item){
        $tags = explode(";",  str_replace(',', ';', $item['tags']));
        foreach ($tags as $t){
            $t = strtolower($t);
            if (array_key_exists($t, $all_tags)){
                $all_tags[$t] = $all_tags[$t] + 1;
            }else{
                $all_tags[$t] = 1;
            }
        }
    }
    arsort($all_tags);
    foreach (array_keys($all_tags) as $tag){
        if ($tag){
            $freq = $all_tags[$tag];
            echo "<div class='button tag-option' style='opacity:";
            echo pow(($freq/7), 1)+0.4;
            echo ";font-size:";
            echo min(100, map_range($freq, 1, 3, 75, 100));
            echo "%'>";
            echo $tag;
            echo "</div>";
        }
    }
    echo "</div>";
    ?>
    </div>
    </div>

    <div class="form-item">
    <h2>When to publish:</h2>
    <input id="form-date-published" type="text" name="date_published" value="Immediately">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i><br>
    <div class="tooltip hidden">The date and time (24h format) when this product should be published, in the format: <q>YYYY/MM/DD HH:MM:SS</q>.<br>(e.g. <q>2017/05/22 17:59</q>, or just <q>2017/05/22</q> which will publish at midnight).</div>
    </div>

    <div class="form-item">
    <h2>Facebook:</h2>
    <input id="form-twitface" type="text" name="twitface" value="New Texture - ##name##: ##link## #free #pbr #texture #cc0 #b3d">
    </div>

    <div class="form-item">
    <h2>Reddit:</h2>
    <input id="form-reddit" type="text" name="reddit" value="##name##">
    <i class="fa fa-question-circle show-tooltip" aria-hidden="true"></i>
    <div class="tooltip hidden">Post to /r/CC0Textures. Leave blank to skip posting to Reddit</div>
    </div>

    <div>
    <button id='submit' class='button'>Submit<i class="fa fa-chevron-right" aria-hidden="true"></i></button>
    </div>


</form>


</div>
</div>

</body>
</html>
