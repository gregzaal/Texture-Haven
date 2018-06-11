</div>  <!-- #push-footer -->
<?php 
echo "<div id='footer'";
if (starts_with($_SERVER['REQUEST_URI'], "/textures/")){
    echo " class='footer-cat'";
}
echo ">";
?>
    <div class='footer-patrons'>
        <h2>Patrons</h2>
        <div class="patron-list">
            <!-- None yet :(<br><a href="https://www.patreon.com/texturehaven">Be the first?</a> -->
            <?php
            foreach ($GLOBALS['PATRON_LIST'] as $p){
                echo "<span class='patron patron-rank-".$p[1]."'>".$p[0]."</span> ";
            }
            ?>
        </div>
        <a href="https://www.patreon.com/texturehaven">
            <div class="button-red">
                Join the ranks, support Texture Haven on Patreon.
            </div>
        </a>
    </div>

    <div class='social'>
        <a href="https://www.facebook.com/texturehaven/"><img src="/files/site_images/icons/facebook.svg"></a>
        <a href="https://twitter.com/TextureHaven"><img src="/files/site_images/icons/twitter.svg"></a>
        <!-- TODO email subscriptions -->
        <!-- <div id='email-form'>
            <form action='https://gumroad.com/follow_from_embed_form' class='form gumroad-follow-form-embed' method='post'>
                <input name='seller_id' type='hidden' value='798267932401'>
                <input name='email' placeholder='Monthly email updates' type='email'><button data-custom-highlight-color='' type='submit'>Subscribe</button>
            </form>
        </div> -->
    </div>

    <ul class='footer-links'>
        <li><a href="/">Home</a></li>
        <li><a href="/p/about-contact.php">About</a></li>
        <li><a href="/p/about-contact.php">Contact</a></li>
        <li><a href="/p/license.php">License</a></li>
        <li><a href="/p/faq.php">FAQ</a></li>
        <li><a href="/p/finance-reports.php">Finance Reports</a></li>
        <!-- <li><a href="/p/stats.php">Stats</a></li> -->
    </ul>
</div>
