<?php
include($_SERVER['DOCUMENT_ROOT'] . '/php/functions.php');

$sql = "SELECT * FROM textures ORDER BY date_published DESC, date_taken ASC";
$conn = db_conn_read_only();
$result = mysqli_query($conn, $sql);
$data = array();
if (mysqli_num_rows($result) > 0) {
  while ($row = mysqli_fetch_assoc($result)) {
    $data[$row['name']] = $row;
  }
}

$json_data = [];

foreach ($data as $asset) {

  $slug = $asset['slug'];

  $asset['categories'] = explode(';', $asset['categories']);
  $asset['tags'] = explode(';', $asset['tags']);
  $asset['authors'] = [$asset['author'] => "All"];
  $asset['date_published'] = strtotime($asset['date_published']);
  $asset['download_count'] = (int) $asset['download_count'];
  $asset['staging'] = (bool) !$asset['is_published'];
  $asset['name'] = nice_name($asset['name']);

  $bool_props = [
    'staging'
  ];
  foreach ($bool_props as $p) {
    if ($asset[$p]) {
      $asset[$p] = (bool) $asset[$p];
    } else {
      unset($asset[$p]);
    }
  }
  if (!$asset['scale']) {
    unset($asset['scale']);
  }

  unset($asset['id']);
  unset($asset['author']);
  unset($asset['seamless']);
  unset($asset['slug']);
  unset($asset['is_published']);

  $json_data[$slug] = $asset;
}



print_ra(json_encode($json_data, JSON_PRETTY_PRINT));

if ($GLOBALS['WORKING_LOCALLY']) {
  file_put_contents("Y:/Poly Haven/polyhaven.com/pages/db_json_textures.json", json_encode($json_data, JSON_PRETTY_PRINT));
}
