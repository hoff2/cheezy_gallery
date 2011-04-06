<?php
define(THUMBNAIL_WIDTH,  200);
define(THUMBNAIL_HEIGHT, 200);
define(FEATURED_MAX_WIDTH, 600);
define(GALLERY_COLUMNS, 3);

$directory = $_GET['dir'];
$feature   = $_GET['img'];

function create_thumbnail($source, $dest, $width, $height) {
  list ($owidth, $oheight, $type) = getimagesize($source);
  if ($owidth  <= $width  && $oheight <= $height) {
    copy($source, $dest);
  } else {
    switch ($type) {
    case IMAGETYPE_GIF:
      $image = imagecreatefromgif($source);
      break;
    case IMAGETYPE_JPEG:
      $image = imagecreatefromjpeg($source);
      break;
    case IMAGETYPE_PNG:
      $image = imagecreatefrompng($source);
      break;
    default:
      trigger_error(
        "Cannot create thumbnail: {$this->filename} is wrong type.");
    }
    if (!$image) trigger_error(
      "{$this->filename} not found");
    $rwidth  = $width  / $owidth;
    $rheight = $height / $oheight;
    $scale   = min($rwidth, $rheight);
    $nwidth  = $owidth  * $scale;
    $nheight = $oheight * $scale;
    $thumb = imagecreatetruecolor($nwidth, $nheight);
    imagecopyresampled(
      $thumb, $image, 0, 0, 0, 0, $nwidth, $nheight, $owidth, $oheight);
    switch ($type) {
    case IMAGETYPE_GIF:
      imagegif( $thumb, $dest);
      break;
    case IMAGETYPE_JPEG:
      imagejpeg($thumb, $dest);
      break;
    case IMAGETYPE_PNG:
      imagepng( $thumb, $dest);
      break;
    }
  }
}

function gallery($directory) {
  global $feature;
  $captions_file = "$directory/captions.php";
  if (file_exists($captions_file)) {
    include($captions_file);
  } else {
    $captions = array();
  }
  if ($feature && file_exists("$directory/$feature")) {
    list ($width, $height, $type) = getimagesize("$directory/$feature");
    echo "<div class=\"feature\">";
    echo "<h2>$feature</h2>";
    if ($width > FEATURED_MAX_WIDTH) {
      echo "<img src=\"$directory/$feature\" width=\"90%\" /><br />";
    } else {
      echo "<img src=\"$directory/$feature\" /><br />";
    }
    echo $captions[$feature];
    echo "</div>";
  }
  $dh  = opendir($directory);
  while (false !== ($filename = readdir($dh))) {
    $files []= $filename;
  }
  sort($files);
  $col = 0;
  echo "<table class=\"gallery\">";
  foreach ($files as $file) {
    if (preg_match('/^\.{1,2}|_thumbnails|captions.php$/', $file)) continue;
    if ($col == 0) echo "<tr>";
    echo "<td class=\"thumbnail\">";
    $link = htmlspecialchars(
      preg_replace('/&img=[^&]*/', '', $_SERVER['REQUEST_URI']) .
      '&img=' . urlencode($file));
    echo "<a href=\"$link\">";
    $thumbnail_directory = "$directory/_thumbnails";
    $thumbnail_file      = "$thumbnail_directory/_$file";
    if (!is_dir($thumbnail_directory)) mkdir($thumbnail_directory);
    if (!file_exists($thumbnail_file)) {
      create_thumbnail("$directory/$file", $thumbnail_file,
        THUMBNAIL_WIDTH, THUMBNAIL_HEIGHT);
    }
    echo "<img src=\"$thumbnail_file\" />";
    echo "</a><br />";
    echo $captions[$file];
    echo "</td>";
    $col = ($col + 1) % GALLERY_COLUMNS;
    if ($col == 0) echo "</tr>";
  }
  if ($col != 0) echo "</tr>";
  echo "</table>";
}

function gallery_list($path) {
  $directories = array();
  $dh  = opendir('images');
  while (false !== ($filename = readdir($dh))) {
    if (is_dir("images/$filename")) $directories []= $filename;
  }
  sort($directories);
  $descriptions_file = "images/descriptions.php";
  if (file_exists($descriptions_file)) {
    include($descriptions_file);
  } else {
    $descriptions = array();
  }
  echo "<ul class=\"gallery_list\">";
  foreach ($directories as $directory) {
    if (preg_match('/^\.{1,2}$/', $directory)) continue;
    echo "<li>";
    $link = htmlspecialchars($_SERVER['REQUEST_URI']) .
      '?dir=' . urlencode($directory);
    echo "<a href=\"$link\">$directory</a>";
    if ($descriptions[$directory]) {
      echo ": " . $descriptions[$directory];
    }
    echo "</li>";
  }
}
?>
