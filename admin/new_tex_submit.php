<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Texture | Submit</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="new_tex.js"></script>
</head>
<body>

<div id="page-wrapper">
<div id="page" class="center-all">

<?php

$conn = db_conn_read_write();  // Create Database connection first so we can use `mysqli_real_escape_string`

$name = mysqli_real_escape_string($conn, $_POST["name"]);
$author = mysqli_real_escape_string($conn, $_POST["author"]);
$slug = mysqli_real_escape_string($conn, $_POST["slug"]);


// File checks
// Shamelessly copy-pasta'd from https://www.w3schools.com/php/php_file_upload.asp
$session_hash = random_hash(8);
$target_dir = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "tmp_upload", $session_hash);
qmkdir($target_dir);
// echo "<pre>";
// print_r($_FILES);  // DEBUG
// echo "</pre>";
foreach ($_FILES['texture_maps']['name'] as $i=>$f){
    // Texture Maps
    $tmp_file = $_FILES['texture_maps']['tmp_name'][$i];
    $file_hash = random_hash(8);
    $file_name = basename($f);
    $without_ext = pathinfo($file_name, PATHINFO_FILENAME);
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $target_file = join_paths($target_dir, $file_hash."__".$file_name);
    $uploadOk = 1;
    $error = "";
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($tmp_file);
        if($check == false) {
            $error = "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Allow certain file formats
    $allowed_file_types = ['jpg', 'jpeg', 'png'];
    if (!in_array($ext, $allowed_file_types)){
        $error = "Only JPG and PNG files are supported.";
        $uploadOk = 0;
    }
    if ($uploadOk == 1) {
        if (move_uploaded_file($tmp_file, $target_file)) {
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            $error = "There was an unknown error uploading your file. Please contact Greg and provide the files that aren't working, along with this path: <pre>".$target_file."</pre>";
        }
    }
    if ($uploadOk == 0) {
        echo $error;
        // header("Location: /admin/new_tex.php?error=".$error);
        die();
    }

    // Resolutions
    $standard_resolutions = [1, 2 ,4, 8];  // in 'k' (1k, 2k...)
    $sizearr = getimagesize($target_file);
    $x = $sizearr[0];
    $y = $sizearr[1];
    $res_int = floor(max($x, $y)/1000);
    $resolutions = [];
    foreach ($standard_resolutions as $sr){
        if ($sr <= $res_int-1){
            array_push($resolutions, $sr);
        }
    }
    array_push($resolutions, $res_int);
    // echo "<pre>";
    // print_r($resolutions);  // DEBUG
    // echo "</pre>";
    foreach ($resolutions as $r){
        $res_str = $r.'k';
        $final_dir = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "textures", $slug, $res_str);
        qmkdir($final_dir);
        $final_file = join_paths($final_dir, $without_ext."_".$res_str.".".$ext);
        if ($ext == "png" and !$GLOBALS['WORKING_LOCALLY']){
            $jpg_file = join_paths($final_dir, $without_ext."_".$res_str.".jpg");
            resize_image($target_file, $jpg_file, 'jpg', 1024*$r, 1024*$r, 95);
        }
        if ($r != $res_int){
            if (!$GLOBALS['WORKING_LOCALLY']){
                resize_image($target_file, $final_file, $ext, 1024*$r, 1024*$r, 95);     
            }
        }else{
            rename($target_file, $final_file);
        }
    }

    // Map previews
    $map_type = substr($without_ext, strlen($slug)+1);
    $map_previews = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "tex_images", "map_previews", $slug);
    qmkdir($map_previews);
    $map_preview_f = join_paths($map_previews, $map_type.".jpg");
    if (!$GLOBALS['WORKING_LOCALLY']){
        resize_image($final_file, $map_preview_f, 'jpg', 640, 640, 85);
    }
}
// ZIP for each resolution set, for each extension type
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

    $file_sets = [];
    foreach ($files as $f){
        if ($f != '.' and $f != '..' and str_contains($f, '.')){
            $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
            if ($ext != 'zip'){
                $file_sets[$ext][$f] = 1;
            }
        }
    }

    foreach (array_keys($file_sets) as $ext){
        $all_maps_f = $slug.'_'.$r.'_'.$ext.".zip";
        $zip_fp = join_paths($res_dir, $all_maps_f);
        $zip = new ZipArchive;
        $zip->open($zip_fp, ZipArchive::CREATE);
        foreach (array_keys($file_sets[$ext]) as $f){
            $fp = join_paths($res_dir, $f);
            $content = file_get_contents($fp);
            $zip->addFromString(pathinfo ( $fp, PATHINFO_BASENAME), $content);
        }
        $zip->close();
    }
}

// Sphere render
$target_dir = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "tex_images", "spheres");
qmkdir($target_dir);
$f = $_FILES['sphere_render']['name'];
$tmp_file = $_FILES['sphere_render']['tmp_name'];
$file_hash = random_hash(8);
$file_name = basename($f);
$target_file = join_paths($target_dir, $slug.".png");
$uploadOk = 1;
$error = "";
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($tmp_file);
    if($check == false) {
        $error = "File is not an image.";
        $uploadOk = 0;
    }
}
// Allow certain file formats
$ext = strtolower(pathinfo(basename($f),PATHINFO_EXTENSION));
if ($ext != 'png'){
    $error = "Sphere render must be a PNG";
    $uploadOk = 0;
}
if ($uploadOk == 1) {
    if (move_uploaded_file($tmp_file, $target_file)) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
        $error = "There was an unknown error uploading your file. Please contact Greg and provide the files that aren't working, along with this path: <pre>".$target_file."</pre>";
    }
}
if ($uploadOk == 0) {
    echo $error;
    // header("Location: /admin/new_tex.php?error=".$error);
    die();
}
// Make JPG with correct background color
if (!$GLOBALS['WORKING_LOCALLY']){
    $jpg_file = join_paths($target_dir, $slug.".jpg");
    $img = new imagick();
    $img->newImage(640, 640, "rgb(240, 240, 240)");
    $tmp_img = new imagick($target_file);
    $img->compositeimage($tmp_img, Imagick::COMPOSITE_OVER, 0, 0);
    $img->setImageFormat('jpg');
    $img->setImageCompression(Imagick::COMPRESSION_JPEG);
    $img->setImageCompressionQuality(90);
    $img->writeImage($jpg_file);
    // TODO thumbnail for tex grid
}



// Database stuff
$sql_fields = [];
$sql_fields['name'] = $name;
$sql_fields['author'] = $author;
$sql_fields['slug'] = $slug;
function format_tagcat($s, $conn){
    $s = trim(str_replace(",", ";", str_replace(", ", ";", $s)), ",");
    return mysqli_real_escape_string($conn, $s);
}
$categories = format_tagcat($_POST["cats"], $conn);
$sql_fields['categories'] = $categories;
$sql_fields['tags'] = format_tagcat($_POST["tags"], $conn);

$date_published = $_POST["date_published"];
if ($date_published != "Immediately"){
    $sql_fields['date_published'] = $_POST["date_published"];
}
if (isset($_POST['seamless'])) {
    $sql_fields['seamless'] = "1";
}

// XXX
// echo "<pre>";
// print_r($sql_fields);
// echo "</pre>";

foreach (array_keys($sql_fields) as $k){
    $sql_fields[$k] = "'".$sql_fields[$k]."'";
}
$sql_value_str = implode(", ", array_values($sql_fields));
$sql_field_str = implode(", ", array_keys($sql_fields));

$sql = "INSERT INTO textures (".$sql_field_str.") VALUES (".$sql_value_str.")";

// XXX
// echo "<br>";
// echo "<br>";
// echo "<br>";
// echo $sql;
$result = mysqli_query($conn, $sql);

if ($result == 1){
    echo "<h1>Success!</h1>";
    echo "<p>";
    echo "<em>".$_POST["name"]."</em> ";  // Use GET instead of $name since $name with apostrophy will show Apostro\'phy instead of Apostro'phy
    echo "successfully added to the database.";
    echo "</p>";
    echo "<p>If you need to edit or update this texture, you can do so from the <a href='https://east1-phpmyadmin.dreamhost.com/sql.php?server=1&db=texturehaven&table=textures&pos=0'>phpMyAdmin interface</a>.</p>";
    
    echo '<a href="/admin" class="no-underline">';
    echo '<div class="button"><i class="fa fa-home" aria-hidden="true"></i> Admin Home</div>';
    echo '</a> ';
    echo '<a href="/admin/new_tex.php" class="no-underline">';
    echo '<div class="button"><i class="fa fa-plus" aria-hidden="true"></i> Add Another</div>';
    echo '</a> ';
    echo '<a href="https://texturehaven.com/tex/?t='.$slug.'" class="no-underline">';
    echo '<div class="button"><i class="fa fa-eye" aria-hidden="true"></i> View This Texture</div>';
    echo '</a> ';

    // Social Media
    // $primary_cats = ["studio", "night", "indoor", "urban", "overcast", "outdoor"];  // In order of preference
    // $pcat = "";
    // foreach ($primary_cats as $c){
    //     if (str_contains($categories, $c)){
    //         $pcat = nice_name($c);
    //         break;
    //     }
    // }
    // $vars = [
    //     "category" => $pcat,
    //     "name" => $name,
    //     "link" => "https://texturehaven.com/tex/?h=".$slug,
    // ];
    // function format_vars($str, $vars){
    //     foreach (array_keys($vars) as $v){
    //         $str = str_replace("##".$v."##", $vars[$v], $str);
    //     }
    //     return str_replace("  ", " ", $str);
    // }
    // $sql_fields = [];
    // $sql_fields['twitface'] = format_vars(mysqli_real_escape_string($conn, $_POST["twitface"]), $vars);
    // $sql_fields['reddit'] = format_vars(mysqli_real_escape_string($conn, $_POST["reddit"]), $vars);
    // $sql_fields['link'] = "https://texturehaven.com/tex/?t=".$slug;
    // $sql_fields['image'] = "https://texturehaven.com/files/tex_images/meta/".$slug.".jpg";
    // $sql_fields['post_datetime'] = date("Y-m-d H:i:s", strtotime('+7 hours', strtotime($date_published)));
    // foreach (array_keys($sql_fields) as $k){
    //     $sql_fields[$k] = "'".$sql_fields[$k]."'";
    // }
    // $sql_value_str = implode(", ", array_values($sql_fields));
    // $sql_field_str = implode(", ", array_keys($sql_fields));
    // $sql = "INSERT INTO social_media (".$sql_field_str.") VALUES (".$sql_value_str.")";
    // $result = mysqli_query($conn, $sql);

}else{
    echo "<h1>Submission Failed.</h1>";

    // Check for existing
    $existing_sql = "SELECT * from textures WHERE slug='".$slug."'";
    $existing_result = mysqli_query($conn, $existing_sql);
    if (mysqli_num_rows($existing_result) > 0){
        echo "<p>There is already a texture with the slug <em>".$slug."</em></p>";
        echo "<p>Either choose a different slug, or manually remove the existing one from the database.</p>";
    }else{
        echo "<p>Looks like something went wrong :(<br>Here is the generated SQL query to help you figure out the problem:</p>";
        echo "<p>".$sql."</p> ";
    }

    echo '<a href="javascript:history.back()" class="no-underline">';
    echo '<div class="button"><i class="fa fa-chevron-left" aria-hidden="true"></i> Back</div>';
    echo '</a> ';
    echo '<a href="javascript:window.location.href=window.location.href" class="no-underline">';
    echo '<div class="button"><i class="fa fa-refresh" aria-hidden="true"></i> Try Again</div>';
    echo '</a> ';
}


$conn->close();

?>



</div>
</div>


</body>
</html>