<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("FAQ");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');
?>

<div id="page-wrapper">
    <h1>Frequently Asked Questions</h1>

    <!-- TODO -->

    <div class="anchor-wrapper"><a class="anchor" name="what"></a></div>
    <a href="#what"><h2>What is a scanned texture ?</h2></a>
    <p>
        The traditional texture consists of a photograph of a certain surface, which you can then use in a 3D program. However, it is not possible to get accurate depth data from these single based photo textures. 
        With photo scans you use multiple photos of a surface so you can read the depth with scan software. This ensures accurate depth maps such as Normal, Displacement and AO. with today's PBR standard scanned textures are essential

    </p>


</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
