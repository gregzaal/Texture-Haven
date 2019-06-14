<?php

// Track which texture map was downloaded, at what resolution and file format - for statistical purposes.
// IP addresses are stored (after obfuscation) so that we can count the number of unique downloads of a texture,
// ignoring the same person downloading different maps of the same texture

include ($_SERVER['DOCUMENT_ROOT'].'/php/functions.php');

if(isset($_POST['id']) and isset($_POST['fhash'])){
    $id = $_POST['id'];
    $file_hash = $_POST['fhash'];

    $conn = db_conn_read_write();

    // Main download_counting table
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        // Use original IP instead of Cloudflare node IP
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $ip_hash = simple_hash($_SERVER['REMOTE_ADDR']);
    $sql = "INSERT INTO download_counting (`ip`, `tex_id`, `file_hash`) ";
    $sql .= "VALUES (\"".$ip_hash."\", \"".$id."\", \"".$file_hash."\")";
    $result = mysqli_query($conn, $sql);

    // Texture table
    $sql = "UPDATE textures SET download_count=download_count+1 WHERE id='".$id."'";
    $result = mysqli_query($conn, $sql);
}

?>
