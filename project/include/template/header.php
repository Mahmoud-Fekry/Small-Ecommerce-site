<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php getTitle(); ?></title>
		<link rel="stylesheet" href="<?php echo $css ?>bootstrap.min.css" />
		<link rel="stylesheet" href="<?php echo $css ?>font-awesome.min.css" />
        <link rel="stylesheet" href="<?php echo $css ?>jquery-ui.min.css" />
        <link rel="stylesheet" href="<?php echo $css ?>jquery.selectBoxIt.css" />
		<link rel="stylesheet" href="<?php echo $css ?>user-style.css" />
	</head>
	<body>

        <div class="upper-bar">
            <div class="container">
            <?php
                if(isset($_SESSION['user'])){

            ?>

                <div class="text-center my-info">
                    <img class="my-image img-thumbnail img-circle" src="layout/images/items/item.jpg" alt="">
                    <span><?php echo $_SESSION['user'] ?></span>
                </div>
                <ul class="list-unstyled navbar-right">

                    <li><a href="profile.php"> My Profile</a></li> -
                    <li><a href="newads.php"> New Item</a></li> -
                    <li><a href="profile.php#my-item"> My Items</a></li> -
                    <li><a href="logout.php"> Logout</a></li>
                </ul>


                    <?php

                        $user_status = checkUserStatus($session_user);

                        if($user_status == 1){
                            echo '</br>Your MemberShip need to active!!';
                        }
                    } else{
                ?>
                        <a href="login.php">
                            <span class="pull-right">Login / Sign Up</span>
                        </a>
                <?php
                    }
                ?>
            </div>
        </div>
            <nav class="navbar navbar-inverse">
                <div class="container">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php"> Dashboard </a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse navbar-right" id="app-nav">
                        <ul class="nav navbar-nav ">
                            <?php
                                foreach (getAllFrom('categories', 'Cat_ID') as $cat){
                                    if($cat['Cat_Parent'] == 0){
                                        echo '<li><a href="categories.php?pageId=' . $cat['Cat_ID'] .'&pageName=' . str_replace(' ', '-', $cat['Cat_Name']) .'">' . $cat['Cat_Name'] . '</a></li>';
                                    }
                                }
                            ?>
                        </ul>

                    </div>
                </div>
            </nav>
	