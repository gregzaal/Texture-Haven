<?php

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_top.php');

$latest_version = "1.1";
$available_versions = [
    "1.0",
    "1.1",
];
$version = $latest_version;
if (isset($_GET["v"]) && trim($_GET["v"])){
    $v = $_GET["v"];
    if (in_array($v, $available_versions)){
        $version = $v;
    }else{
        http_response_code(404);
        echo "Version {$v} not found. Available versions: ";
        echo json_encode($available_versions, JSON_PRETTY_PRINT);
        die();
    }
}

$items = get_from_db();
$json = array();

$json['version'] = $version;
$json['latest_version'] = $latest_version;
$json['last_updated'] = time();

$assets = array();
foreach ($items as $i){
    $slug = $i['slug'];
    $a = array();
    $a['author'] = $i['author'];
    $a['date_published'] = strtotime($i['date_published']);
    $a['license'] = "CC0";

    $tags = $i['tags'].";".$i['categories'];
    $tags = explode(';', $tags);
    $a['tags'] = $tags;

    $downloads = [];
    $base_dir = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "textures");
    $extensions = array_reverse(listdir($base_dir, "FOLDERS"));  // Reverse so ZIP is first so 'All Maps' is at the top.
    foreach ($extensions as $ext){
        $ext_dir = join_paths($base_dir, $ext);
        $resolutions = listdir($ext_dir, "FOLDERS");
        foreach ($resolutions as $res){
            $res_dir = join_paths($ext_dir, $res);
            if (ends_with($res, 'k')){
                $tex_dir = join_paths($res_dir, $slug);
                if (file_exists($tex_dir)){
                    $files = listdir($tex_dir, "FILES");
                    foreach ($files as $f){
                        $format = $ext;
                        if ($ext == 'zip'){
                            $map_type = 'all';
                            $f_split = explode('_', $f);
                            $format = str_replace('.zip', '', array_pop($f_split));  // Get only 'jpg' from fname
                        }else{
                            $without_ext = pathinfo($f, PATHINFO_FILENAME);
                            $map_type = substr($without_ext, strlen($slug)+1, strlen($res)*-1-1);
                        }
                        $local_url = join_paths($GLOBALS['SYSTEM_ROOT'], "files", "textures", $format, $res, $slug, $f);
                        if (file_exists($local_url)){
                            $url = "https://texturehaven.com/files/textures/{$format}/{$res}/{$slug}/{$f}";
                            if (!array_key_exists($map_type, $downloads)){
                                $downloads[$map_type] = [];
                            }
                            if (!array_key_exists($res, $downloads[$map_type])){
                                $downloads[$map_type][$res] = [];
                            }
                            if (version_compare($version, '1.1', '>=')) {
                                $file = array();
                                $file['url'] = $url;
                                $file['mtime'] = filemtime($local_url);
                                $file['size'] = filesize($local_url);
                                array_push($downloads[$map_type][$res], $file);
                            } else {
                                array_push($downloads[$map_type][$res], $url);
                            }
                        }
                    }
                }
            }
        }
    }
    $a['files'] = $downloads;

    $assets[$slug] = $a;
}
$json['assets'] = $assets;

echo json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_bottom.php');

?>
