<?php
if (!in_array($_SERVER['PHP_SELF'], $GLOBALS['NO_CACHE'])){
    include($_SERVER['DOCUMENT_ROOT'].'/php/html/cache_top.php');
}
?>
<html>
<head>
    <title>%TITLE%</title>

    <!-- CSS -->
    <link href='<?php content_hashed_url("/css/style.css"); ?>' rel='stylesheet' type='text/css' />
    <link href='<?php content_hashed_url("/css/style_medium.css"); ?>' rel='stylesheet' media='screen and (max-width: 1299px)' type='text/css' />
    <link href='<?php content_hashed_url("/css/style_small.css"); ?>' rel='stylesheet' media='screen and (max-width: 759px)' type='text/css' />

    <!-- Google Fonts and Icons -->
    <link href="https://fonts.googleapis.com/css?family=Lato:900,400|Roboto:900,500,300" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">

    <!-- Meta -->
    <meta name="description" content="%DESCRIPTION%"/>
    <meta name="keywords" content="%KEYWORDS%">
    <meta name="author" content="%AUTHOR%">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="rgb(234, 91, 12)">

    <link rel="canonical" href="%URL%" />

    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="%METATITLE%" />
    <meta property="og:description" content="%DESCRIPTION%" />
    <meta property="og:url" content="%URL%" />
    <meta property="og:site_name" content="Texture Haven" />
    <meta property="og:image" content="%FEATURE%" />

    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="utf-8" http-equiv="encoding">

    %TEXTURESONE%

    <!-- jQuery -->
    <script src="/js/jquery.min.js"></script>
    %GALLERYJS%

    <script src='<?php content_hashed_url("/core/core.js"); ?>'></script>
    <script src='<?php content_hashed_url("/js/functions.js"); ?>'></script>
    %LANDINGJS%

    <!-- Google analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-120136024-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-120136024-1');
    </script>

</head>
<body>

<div class="main-wrapper">
