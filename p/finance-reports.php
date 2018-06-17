<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
include_start_html("Finance Reports");
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/header.php');
?>

<div id="page-wrapper">
    <h1>Finance Reports</h1>
    <p>
        It's still early days :) The first month of payouts hasn't gone through yet, so there is nothing to see here (yet).
    </p>
    <p>If you have any questions, feel free to email me at <?php insert_email() ?></p>

</div>

<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/footer.php');
include ($_SERVER['DOCUMENT_ROOT'].'/php/html/end_html.php');
?>
