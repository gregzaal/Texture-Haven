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
        My name is Rob Tuytel, I’m from the Netherlands and I mainly work as an environment designer. I produce VR projects with historical backgrounds and I also teach a course on the Udemy platform for the last few years.
    </p>

    <div style="clear: both"></div>

    <h1>About</h1>
    <p>
        All textures here are <a href="/p/license.php">CC0</a> (public domain). No paywalls, accounts or email spam. Just download what you want, and use it however.
    </p>

    <h1>Get Involved</h1>
    <p>
        Since all of the income for this site comes from the community, it's only fair that the community gets to decide what happens with it.
    </p>
    <p>
        All Patrons have access to a private Trello board where they can add ideas and vote on new types of textures, and generally decide where the money goes.
    </p>
    <p>
        If you want to get involved and help keep this site alive at the same time, consider supporting <a href="https://www.patreon.com/texturehaven">Texture Haven on Patreon</a>.
    </p>

    <h1>Contact</h1>
    <p>Got a question? Please read the <a href="/p/faq.php">FAQ</a> first :)</p>
    <p>The easiest ways to get hold of me is through email: <?php info@hdrihaven.com() ?></p>

</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
