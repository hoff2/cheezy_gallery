<?php require 'cheezy_gallery.php' ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
  <head>
    <title>
      Gallery<?php if ($directory) echo " : " . htmlspecialchars($directory) ?>
    </title>
     <link rel="stylesheet" type="text/css" charset="utf-8" media="all" href="/style.css" />
  <style>
    table.gallery {
      width: 98%;
    }
    table.gallery td, .feature {
      align: center;
      valign: center;
      text-align: center;
    }
    .feature {
      width: 100%;
    }
    table.gallery td {
      border: solid 1px;
    }
  </style>
  </head>
  <body>
    <div id="wrap">
      <!-- header or something -->
      <h1>
        Gallery<?php if ($directory) echo " : " . htmlspecialchars($directory) ?>
      </h1>
      <div id="main">
      <?php
        if ($directory && is_dir("images/$directory")) {
          gallery("images/$directory");
        } else {
          gallery_list("images");
        }
      ?>
      </div>
      <div id="footer">
        <hr />
      </div>
    </div>
  </body>
</html>
