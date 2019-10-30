<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("Texture Haven");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');
?>

<div id='landing-banner-wrapper'>
    <div id='banner-img-a'>
        <div class='banner-img-credit'>Render by Rob Tuytel</div>
    </div>
    <div id='banner-img-b' class='hide'>
        <div class='banner-img-credit'></a></div>
    </div>
    <div id='banner-img-paddle-l' class='banner-img-paddle'><i class="material-icons">keyboard_arrow_left</i></div>
    <div id='banner-img-paddle-r' class='banner-img-paddle'><i class="material-icons">keyboard_arrow_right</i></div>


    <div id='banner-title-wrapper'>
        <img src="/core/img/Texture Haven Logo.svg" id="banner-logo" />
        <p>100% Free Textures, for Everyone.</p>
    </div>
</div>

<div id='landing-page'>

    <div class="segment-b">
        <div class="segment-inner">
            <div class="col-2">
                <h1>100% Free</h1>
                <p>All textures are licenced as <b>CC0</b> and can be downloaded instantly, giving you complete freedom.</p>
                <p>No paywalls, email forms or account systems.</p>
                <a href="/p/license.php">
                    <div class='button'>Read More</div>
                </a>
            </div>

            <div class="col-2">
                <h1>8k, PBR</h1>
                <p>Free stuff and quality stuff are not always mutually exclusive.</p>
                <p>Textures here are some of the best you'll find, they're not just photos, but scanned map sets for the full PBR experience.</p>
                <a href="/textures">
                    <div class='button'>Browse Textures</div>
                </a>
            </div>
        </div>
    </div>

    <div class="segment-a">
        <div class="segment-inner">

            <h1>Supported by you<img src="/files/site_images/icons/heart.svg" class='heart'></h1>
            <div class="col-2">
                <h2 class="patreon-stat" id="patreon-num-patrons"><?php echo sizeof($GLOBALS['PATRON_LIST']) ?> patrons</h2>
            </div>
            <div class="col-2">
                <h2 class="patreon-stat" id="patreon-income">$<?php echo $GLOBALS['PATREON_EARNINGS'] ?> per month</h2>
            </div>

            <div class='patreon-bar-wrapper'>
                <div class="patreon-bar-outer">
                    <div class="patreon-bar-inner-wrapper">
                        <div class="patreon-bar-inner" style="width: <?php echo $GLOBALS['PATREON_CURRENT_GOAL']['completed_percentage'] ?>%"></div>
                    </div>
                </div>
                <div class="patreon-current-goal">Current goal: <b><?php
                    echo goal_title($GLOBALS['PATREON_CURRENT_GOAL']);
                    echo " ($";
                    echo $GLOBALS['PATREON_CURRENT_GOAL']['amount_cents']/100;
                    echo ")";
                ?></b><i class="material-icons hide-mobile">arrow_upward</i></div>
            </div>

            <div class="text-block">
                <p>With Patreon, you can contribute to help us pay the server cost and develop new texture scans. When a goal is achieved, something new will come. Think unlocking 8k textures, PNG files, increasing uploads and more.</p>
                <p>Not only can you contribute financially, but you can get directly involved in the process, helping decide what textures we shoot, and ultimately the direction this site is going.</p>
                <p>Spendings of donation each month is verified by <a href="/p/finance-reports.php" target="_blank">public finance reports</a>.</p>
            </div>

            <a href="https://www.patreon.com/texturehaven/overview" target="_blank">
                <div class='button-inline'>Read More / Become a Patron<img src="/files/site_images/icons/heart_white.svg" class='heart-inline'></div>
            </a>
        </div>
    </div>

    <div class="segment-montage">
        <a href="/textures">
            <div class='button'>Browse Textures</div>
        </a>
    </div>

    <div class="segment-a">
        <div class="segment-inner segment-about">
            <h1>About</h1>
            <img class='me' src="/files/site_images/me.jpg">
            <p>
                My name is Rob Tuytel, I'm from the Netherlands and I mainly work as an environment designer. I produce VR projects with historical backgrounds and I also teach a course on the Udemy platform for the last few years.
            </p>
            <p>
                I spend a lot of time on making textures, which I use for my 3D environment scenes.
            </p>
            <p>
                Texture Haven is a website where you can find high quality scanned textures for free, no catch. All textures here are <a href="/p/license.php">CC0</a> (public domain). No paywalls, accounts or email spam. Just download what you want, and use it for every purpose.
            </p>
            <p>
                If you like what I do and want to keep this site alive, consider <a href="https://www.patreon.com/texturehaven/overview">supporting me on Patreon</a>.
            </p>
        </div>
        <div style="clear: both"></div>
    </div>

</div>  <!-- #landing-page -->

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
