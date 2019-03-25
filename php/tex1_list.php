<?php

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_top.php');

$textures = get_from_db();
$a = [];
foreach ($textures as $t){
    array_push($a, "https://texturehaven.com/tex/?t=".$t['slug']);
}
echo implode(',', $a);

include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_bottom.php');

?>
