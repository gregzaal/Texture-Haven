<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
$num_cache_files = clear_cache();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Clear Page Cache</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

<?php include ($_SERVER['DOCUMENT_ROOT'].'/admin/header.php'); ?>
<div id="page-wrapper">
    <div class="page center-all">
        <p>
            The PHP cache has now been cleared!<br/><b><?php echo $num_cache_files ?> files</b> were removed.
        </p>
        <p>
            Note that the CDN may still be serving cached content, which automatically refreshes every few hours.<br>
            If you absolutely need to clear that cache too, contact Greg.
        </p>
        <a href="javascript:history.back()" class="no-underline">
            <div class="button"><i class="fa fa-chevron-left" aria-hidden="true"></i> Go Back</div>
        </a>
        <a href="javascript:window.location.href=window.location.href" class="no-underline">
            <div class="button"><i class="fa fa-eraser" aria-hidden="true"></i> Clear again</div>
        </a>
    </div>
</div>

</body>
</html>