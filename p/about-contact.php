<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("About / Contact");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');
?>

<div id="page-wrapper">
    <div class='me-wrapper'>
        <img class='me' src="/files/site_images/me.jpg">
    </div>
    <h1>Hi there!</h1>
    <p>
        My name is Rob Tuytel, I'm from the Netherlands and I mainly work as an environment designer. I produce VR projects with historical backgrounds and I also teach a course on the Udemy platform for the last few years.
    </p>

    <div style="clear: both"></div>

    <h1>About</h1>
    <p>
        The purpose of Texture Haven is to provide a platform of high quality PBR textures for free to everyone, with no catch.
    </p>
    <p>
        All textures here are <a href="/p/license.php">CC0</a> (public domain). No paywalls, accounts or email spam. Just download what you want, and use it however.
    </p>
    <p>
        Texture Haven is officially linked with <a href="https://hdrihaven.com">HDRI Haven</a> and <a href="https://3dmodelhaven.com">3D Model Haven</a>.
    </p>

    <?php
    $conn = db_conn_read_only();
    $comm_sponsors = get_commercial_sponsors($conn);
    if (!empty($comm_sponsors)){
        echo "<div class='segment-a'>";
        echo "<div class='segment-inner'>";
        echo "<h2>Corporate Sponsors:</h2>";
        echo "<div class='commercial_sponsors'>";
        foreach ($comm_sponsors as $s){
            echo "<a href= \"".$s['link']."\" target='_blank'>";
            echo "<img src=\"/files/site_images/commercial_sponsors/";
            echo $s['logo'];
            echo "\" alt=\"";
            echo $s['name'];
            echo "\" title=\"";
            echo $s['name'];
            echo "\"/>";
            echo "</a>";
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }
    ?>

    <h1>Get Involved</h1>
    <p>
        Since all of the income for this site comes from the community, it's only fair that the community gets to decide what happens with it.
    </p>
    <p>
        All Patrons have access to a private Trello board where they can add ideas and vote on new types of textures, and generally decide where the money goes.
    </p>
    <p>
        If you want to get involved and help keep this site alive at the same time, consider supporting <a href="https://polyhaven.com/support-us">Texture Haven on Patreon</a>.
    </p>

    <h1>Contact</h1>
    <p>Got a question? Please read the <a href="/p/faq.php">FAQ</a> first :)</p>
    <p>The easiest ways to get hold of me is through email: <?php insert_email() ?></p>

</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
