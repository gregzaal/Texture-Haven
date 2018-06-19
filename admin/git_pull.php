<?php
include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Git Pull</title>
    <link href='/css/style.css' rel='stylesheet' type='text/css' />
    <link href='/css/admin.css' rel='stylesheet' type='text/css' />
    <link href="https://fonts.googleapis.com/css?family=PT+Mono" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
</head>
<body>

<?php include ($_SERVER['DOCUMENT_ROOT'].'/admin/header.php'); ?>
<div id="page-wrapper">
<div id="page">

<h1>Update Website from Git</h1>

<?php 

$cmd = "../utils/update.sh";
$output = htmlspecialchars(shell_exec($cmd));
print_ra ($output);

if ($output != "Already up-to-date.\n"){
    echo "<p>Changes:</p>";
    $cmd = "../utils/show_last_changes.sh";
    $output = htmlspecialchars(shell_exec($cmd));
    print_ra ($output);
}

?>

</div>
</div>

</body>
</html>
