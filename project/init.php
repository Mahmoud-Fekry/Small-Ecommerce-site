<?php

    //Error reporting

    ini_set('display_errors','on');
    error_reporting(E_ALL);

    $session_user='';
    if(isset($_SESSION['user'])) $session_user = $_SESSION['user'];
    
    include 'admin/connect.php';

    $lang_path = 'include/langs/';            //language path
    $func = 'include/functions/';            //functions path
    $temp = 'include/template/';           //template path
    $css = 'layout/css/';                  //css path
    $js = 'layout/js/';                   //js path



    // Include the important file
    include $func . 'functions.php';
    include $lang_path . 'english.php';
    include $temp . 'header.php';
?>