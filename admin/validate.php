<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Validate Textures</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

<?php include ($_SERVER['DOCUMENT_ROOT'].'/admin/header.php'); ?>
<div id="page-wrapper">
<div id="page">

<h1>Validate Texture Files</h1>

<?php 
$items = get_from_db("date_published");

$total_issues = 0;
foreach ($items as $i){
    $issues = [];
    $slug = $i['slug'];
    $base_dir = join_paths($GLOBALS['SYSTEM_ROOT'], "files");
    $paths_to_check = [];
    
    // Thumbnail, sphere
    array_push($paths_to_check, join_paths($base_dir, "tex_images", "thumbnails", $slug.".jpg"));
    array_push($paths_to_check, join_paths($base_dir, "tex_images", "spheres", $slug.".jpg"));
    

    // Everything that is in 1k should be in all other folders too
    $resolutions_path = join_paths($base_dir, "textures", $slug);
    if (!file_exists($resolutions_path)){
        array_push($issues, "All texture files");
    }else{
        $resolutions = scandir($resolutions_path);
        $one_k_path = join_paths($resolutions_path, "1k");
        if (!file_exists($one_k_path)){
            array_push($issues, $one_k_path);
        }else{
            $one_k_files = scandir($one_k_path);
            foreach ($resolutions as $r){
                if ($r != '1k'){
                    if (!str_contains($r, '.')){  // Only folders, not files. is_dir doesn't work on windows, so assume folders don't have '.'
                        foreach ($one_k_files as $ok){
                            if ($ok != '.' and $ok != '..' and str_contains($ok, '.')){
                                $expected_file = str_lreplace('1k', $r, $ok);
                                array_push($paths_to_check, join_paths($resolutions_path, $r, $expected_file));
                            }
                        }
                    }
                }
            }
        }
    }

    // Map previews
    $map_types = [];
    $one_k_path = join_paths($resolutions_path, "1k");
    if (file_exists($one_k_path)){
        $one_k_files = scandir($one_k_path);
        foreach ($one_k_files as $ok){
            if ($ok != '.' and $ok != '..' and str_contains($ok, '.')){
                $without_ext = pathinfo($ok, PATHINFO_FILENAME);
                $ext = strtolower(pathinfo($ok, PATHINFO_EXTENSION));
                if ($ext != 'zip'){
                    $map_type = substr($without_ext, strlen($slug)+1, strlen("1k")*-1-1);
                    array_push($map_types, $map_type);
                }
            }
        }
    }
    $map_previews_path = join_paths($base_dir, "tex_images", "map_previews", $slug);
    foreach ($map_types as $m){
        array_push($paths_to_check, join_paths($map_previews_path, $m.'.jpg'));
    }


    // Do check
    foreach ($paths_to_check as $p){
        if (!file_exists($p)){
            array_push($issues, $p);
        }
    }
    $total_issues += sizeof($issues);

    // Show results
    if ($issues){
        echo "<div class='validate-item'>";
        echo '<i class="fa fa-exclamation-triangle"></i>';
        echo "Missing files for <b>".$slug."</b>:";
        echo "<ul>";
        foreach ($issues as $p){
            $p = str_replace($_SERVER['DOCUMENT_ROOT'], '', $p);  // Make relative to domain
            echo "<li>";
            echo $p;
            echo "</li>";
        }
        echo "</ul>";
        echo "</div>";
    }
}
echo "<p>Total missing files: <b>".$total_issues."</b></p>";

?>

</div>
</div>

</body>
</html>
