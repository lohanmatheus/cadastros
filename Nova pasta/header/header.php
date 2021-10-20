<?php
function pageHeader($title)
{
    echo '<!DOCTYPE html>
        <html lang="pt-br">
        <head>        
            <meta charset="UTF-8">
            <title> '. $title .'</title>
            <link rel="stylesheet" href="../CSS/icons.css">
            <link rel="stylesheet" href="../CSS/search.css">
            <link rel="stylesheet" href="../CSS/bootstrap.css">
            <link rel="stylesheet" href="../CSS/inputs.css">
            <link rel="stylesheet" href="../CSS/spinner.css">
        </head>
        <body class="d-flex flex-column h-100 m-0" style="height: 100vh !important; overflow: hidden !important;">    
        <!-- Preloader -->
        <div id="preloader">
            <div class="spinner"></div>
        </div>';
        require_once(__DIR__.'/nav-bar.php');
        echo '<div class="container-fluid overflow-auto mb-auto pb-3 font-size-12" style="height: 100vh !important;">';

}