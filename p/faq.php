<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("FAQ");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');
?>

<div id="page-wrapper">
    <h1>Frequently Asked Questions</h1>

    <div class="anchor-wrapper"><a class="anchor" name="what"></a></div>
    <a href="#what"><h2>What is a scanned texture?</h2></a>
    <p>
        The traditional texture consists of a photograph of a certain surface, which you can then use in a 3D program. However, it is not possible to get accurate depth data from these single based photo textures.
        With photo scans you use multiple photos of a surface so you can read the depth with scan software. This ensures accurate depth maps such as Normal, Displacement and AO. with today's PBR standard scanned textures are essential

    </p>

    <div class="anchor-wrapper"><a class="anchor" name="how"></a></div>
    <a href="#how"><h2>How do I use these maps?</h2></a>
    <p>
        Applying these maps is very simple. Game engines like Unreal and Unity have a user-friendly interface to add these maps. These maps also work very well in 3D programs such as Blender, Maya and 3DS Max.
    </p>

    <div class="anchor-wrapper"><a class="anchor" name="num-photos"></a></div>
    <a href="#num-photos"><h2>How many photos are taken for a scan?</h2></a>
    <p>
        This varies per surface. On average, between 150 and 250 photos are taken from a surface. the photos are shot in a raw format.
    </p>

    <div class="anchor-wrapper"><a class="anchor" name="equipment"></a></div>
    <a href="#equipment"><h2>What equipment and software do you use?</h2></a>
    <p>
        Photos are taken with a Nikon d5300 with a 35 mm lens. I use lightroom for photo color/lens calibration. Agisoft is used for scanning. After scanning Blender created bakes from the scan output. With photoshop these are made tileable.
    </p>
    <p>
        I use two different systems for generating the scans. My scan desktop uses a intel 4770K including 32 GB ram and a GTX 1080ti for rendering. The other system is used for baking and post processing. This system uses a AMD Threadripper including 84GB ram and a GTX 970.
    </p>

    <div class="anchor-wrapper"><a class="anchor" name="time"></a></div>
    <a href="#time"><h2>How long does it take to complete a scan?</h2></a>
    <p>
        Making a scan start with finding the right location. During a site visit I shoot between 5 / 10 textures. Location visits quickly take a day, You soon have about 120 GB of raw data.
    </p>
    <p>
        Transforming raw data into scanning ready data takes around 30 minutes, but generating the scan in Agisoft takes the most time. There are several options for getting scan outputs, if you want ultra-high resolution you quickly have 24/40 hours processing time. The more Ram you have, the quicker you generate a scan. Post process on the scan takes about two hours.
    </p>

    <div class="anchor-wrapper"><a class="anchor" name="types"></a></div>
    <a href="#types"><h2>What kind of textures can I find on this platform?</h2></a>
    <p>
        There are countless surface usable for generating textures. We try to focus on the most common textures and want to slowly extend this to a wider range. Patreons can indicate which category should receive the most attention.
    </p>

    <div class="anchor-wrapper"><a class="anchor" name="who"></a></div>
    <a href="#who"><h2>Who is currently creating texture scans?</h2></a>
    <p>
        At the moment I am ( Rob Tuytel) responsible for the variety of textures on this platform. In this way I can guarantee quality and apply structure. Yet I want texture Haven to make a platform available to everyone who would like to contribute something. In the future roadmap I will quickly show what the idea is to make texture Haven a common platform for contributors.
    </p>



</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
