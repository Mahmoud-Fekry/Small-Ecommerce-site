<?php

session_start();
if(isset($_SESSION['Username'])){
    $pageTitle = "Dashboard";   //to show page title in title bar
    include 'init.php';

    // Define variables
    
    $usersNum = 4;                                                         //variable to define number of latest users
    $latestUsers = getLatest("*", "users", "User_ID", $usersNum);            //array contain latest users info

    $itemsNum=4;                                                           //variable to define number of latest items
    $latestItems = getLatest("*", "items", "Item_ID", $itemsNum);             //array contain latest items info

    $commentsNum=3;                                                           //variable to define number of latest comments
    $latestcomments = getLatest("*", "comments", "Comment_ID", $commentsNum);             //array contain latest comments0
?>
    <div class="home-stat text-center">
        <div class="container">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members  clearfix">
                        <a href="member.php">
                            <i class="d-icon fa fa-users fa-4x"></i>
                            <span class="info">
                                Total Members
                                <span><?php echo countItem('User_ID', 'users'); ?></span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending  clearfix">
                        <a href="member.php?do=Manage&page=pending">
                            <i class="d-icon fa fa-user-plus fa-4x"></i>
                            <span class="info">
                                Pending Members
                                <span><?php echo countItem('Reg_Status', 'users', 0); ?></span>
                            </span>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items clearfix">
                        <a href="items.php">
                            <i class="d-icon fa fa-tag fa-4x"></i>
                            <span class="info">
                                Total Items
                                <span><?php echo countItem('Item_ID', 'items'); ?></span>
                            </span>
                        </a>

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments clearfix">
                        <a href="comments.php">
                            <i class="d-icon fa fa-comments fa-4x"></i>
                            <span class="info">
                                Total Comments
                                <span><?php echo countItem('Comment_ID', 'comments'); ?></span>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!--********************************************* Start latest users and items *********************************************-->
    <div class="latest">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i> Latest <?php echo $usersNum ?> Registerd Users
                            <span class="toggle-info pull-right">
                                <i class="fa fa-minus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                                echo '<ul class="list-unstyled latest-users">';
                                if(!empty($latestUsers)){
                                    foreach($latestUsers as $user){

                                        //Display latest users
                                        echo '<li>';
                                        echo $user['User_Name'];

                                        //Display edit button

                                        echo '<a href="member.php?do=Edit&UserId=' . $user['User_ID'] . '">';
                                        echo '<span " class="btn btn-success pull-right">';
                                        echo '<i class="fa fa-edit"></i>Edit';
                                        echo '</span>';
                                        echo '</a>';

                                        //Check if user not active then display active button

                                        if($user['Reg_Status'] == 0){
                                            echo '<a href="member.php?do=Activate&UserId=' . $user['User_ID'] . '">';
                                            echo '<span " class="btn btn-info pull-right">';
                                            echo '<i class="fa fa-edit"></i>Activate';
                                            echo '</span>';
                                            echo '</a>';
                                        }
                                        echo '</li>';
                                    }
                                }
                                else{
                                    echo 'There is no user to show';
                                }

                                echo '</ul>';
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $itemsNum ?> Items
                            <span class="toggle-info pull-right">
                                <i class="fa fa-minus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                                echo '<ul class="list-unstyled latest-users">';
                                if(!empty($latestItems)){
                                    foreach($latestItems as $item){

                                        //Display latest items
                                        echo '<li>';
                                        echo $item['Item_Name'];

                                        //Display edit button

                                        echo '<a href="items.php?do=Edit&itemId=' . $item['Item_ID'] . '">';
                                        echo '<span " class="btn btn-success pull-right">';
                                        echo '<i class="fa fa-edit"></i>Edit';
                                        echo '</span>';
                                        echo '</a>';

                                        //Check if item not approved then display approve button

                                        if($item['Item_Approve'] == 0){
                                            echo '<a href="items.php?do=Approve&ItemId=' . $item['Item_ID'] . '">';
                                            echo '<span " class="btn btn-info pull-right">';
                                            echo '<i class="fa fa-edit"></i>Approve';
                                            echo '</span>';
                                            echo '</a>';
                                        }
                                        echo '</li>';
                                    }
                                }
                                else {
                                    echo 'There is no items to show';
                                }
                                echo '</ul>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <!--******************* Start latest comment --*******************-->
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tag"></i> Latest <?php echo $commentsNum ?> comments
                            <span class="toggle-info pull-right">
                                <i class="fa fa-minus fa-lg"></i>
                            </span>
                        </div>
                        <div class="panel-body">
                            <?php
                            $stmt = $con->prepare("SELECT 
                                                                comments.*,
                                                                users.User_Name as user_name
                                                       FROM
                                                                comments
                                                      INNER JOIN 
                                                                users
                                                      ON 
                                                                users.User_ID = comments.User_Id");
                            $stmt->execute();
                            $comments_rows=$stmt->fetchAll();

                            if(!empty($comments_rows)){
                                foreach ($comments_rows as $comment){
                                    echo '<div class="comment-box">';
                                    echo '<span class="user-name">' . $comment['user_name'] . '</span>';
                                    echo '<span class="user-comment">' . $comment['Comment_Value'] . '</span>';
                                    echo '</div>';
                                }
                            }
                            else{
                                echo 'There is no comment to show';
                            }


                            echo '</ul>';
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--********************************************* Start latest users and items *********************************************-->

<?php
}
else{
    header('location: index.php');
    exit();
}
?>
    <?php include $temp . "footer.php"; ?>