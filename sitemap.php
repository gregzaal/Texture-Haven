<?php
header('Content-type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

// Main Pages
$urls = [
    ["https://texturehaven.com/", "monthly"],
    ["https://texturehaven.com/textures/", "weekly"],
    ["https://texturehaven.com/p/about-contact.php", "monthly"],
];
foreach ($urls as $u){
    echo "<url>";
    echo "<loc>".$u[0]."</loc>";
    echo "<changefreq>".$u[1]."</changefreq>";
    echo "<priority>0.5</priority>";
    echo "</url>";
}

// Textures
$textures = get_from_db();
foreach ($textures as $t){
    echo "<url>";
    echo "<loc>https://texturehaven.com/tex/?t=".$t['slug']."</loc>";
    echo "<lastmod>".date("Y-m-d", strtotime($t['date_published']))."</lastmod>";
    echo "<priority>0.8</priority>";
    echo "<changefreq>monthly</changefreq>";
    echo "<image:image>";
    echo "<image:loc>https://texturehaven.com/files/tex_images/spheres/".$t['slug'].".jpg</image:loc>";
    echo "</image:image>";
    echo "</url>";
}

// Categories
$cats = get_all_categories();
foreach ($cats as $c){
    if ($c){
        if ($c != 'all'){
            echo "<url>";
            echo "<loc>https://texturehaven.com/textures/?c=".$c."</loc>";
            echo "<priority>0.5</priority>";
            echo "<changefreq>weekly</changefreq>";
            echo "</url>";
        }
    }
}

echo '</urlset>';
?>