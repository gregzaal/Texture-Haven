<?php

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_top.php');

$textures = get_from_db();
$json = array();
foreach ($textures as $t){
    $t = $t['tags'].";".$t['categories'];
    $json[$t['slug']] = $t;
}

echo json_encode($json, JSON_PRETTY_PRINT);

include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_bottom.php');

?>
