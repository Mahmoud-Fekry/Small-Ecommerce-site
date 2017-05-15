<?php
    
include 'connect.php';

$lang_path = 'include/langs/';            //language path
$func = 'include/functions/';            //functions path
$temp = 'include/template/';           //template path
$css = 'layout/css/';                  //css path
$js = 'layout/js/';                   //js path



// Include the important file
include $func . 'functions.php';
include $lang_path . 'english.php';
include $temp . 'header.php';

//Include navbar on all pages expect page has  $noNavBar variable

if(!isset($noNavbar)){ include  $temp . 'navbar.php'; }