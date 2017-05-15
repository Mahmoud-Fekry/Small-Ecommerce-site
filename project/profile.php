<?php

    session_start();

    $pageTitle = "Profile";

    include 'init.php';

    if(isset($_SESSION['user'])){

        $getUser = $con->prepare("SELECT * FROM users WHERE User_Name=?");

        $getUser->execute(array($session_user));

        $info = $getUser->fetch();
?>

        <!--******************* Display personal information *******************-->

        <h1 class="text-center"> My Profile</h1>
        <div class="information block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading ">My Information</div>
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li>
                                <i class="fa fa-unlock-alt fa-fw"></i>
                                <span>Name </span>:
                                <?php echo $info['User_Name'];?>
                            </li>
                            <li>
                                <i class="fa fa-envelope-o fa-fw"></i>
                                <span>Email </span>:
                                <?php echo $info['Email'];?>
                            </li>
                            <li>
                                <i class="fa fa-user fa-fw"></i>
                                <span>Full Name </span>:
                                <?php echo $info['Full_Name'];?>
                            </li>
                            <li>
                                <i class="fa fa-calendar fa-fw"></i>
                                <span>Registeration Date </span>:
                                <?php echo $info['User_Add_Date'];?>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>

        <!--************************ display all Items for this user ************************-->

        <div id="my-item" class="my-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Items</div>
                    <div class="panel-body">
                        <?php

                            // if there is any ads display it

                            if(!empty(getItem('Member_Id', $info['User_ID']))){

                                echo'<div class="row">';

                                foreach (getItem('Member_Id', $info['User_ID'], 1) as $item){
                                    ?>
                                        <div class="col-sm-6 col-md-3">
                                            <div class="thumbnail item-box">
                                                <span class="item-price"><?php echo '$' . $item['Item_Price']; ?></span>

                                                 <?php

                                                    //display not approved to item that noot approved

                                                    if($item['Item_Approve'] == 0){
                                                        echo '<span class="not-approve">Not approved</span>';
                                                    }
                                                 ?>

                                                <img class="img-responsive" src="layout/images/items/item.jpg" alt=""/>
                                                <div class="caption">
                                                    <h3><a href="items.php?itemId=<?php echo $item['Item_ID'] ?>"><?php echo $item['Item_Name']; ?></a></h3>
                                                    <p class="description"><?php echo $item['Item_Description']; ?></p>
                                                    <span class="add-date"><?php echo $item['Add_Date'] ?></span>
                                                </div>
                                            </div>
                                        </div>
                                 <?php
                                }

                                echo '</div>';

                            } else{

                                echo 'There is no ads to show. create <a  href="newads"class="new-ads"> new ad </a>';

                            }
                       ?>
                    </div>
                </div>

            </div>
        </div>

        <!--************************ display all comments for this user ************************-->

        <div class="my-comments block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">Latest Comments</div>
                    <div class="panel-body">
                        <?php

                            $stmt = $con->prepare("SELECT * FROM comments WHERE User_Id=?");

                            $stmt->execute(array($info['User_ID']));

                            $comments=$stmt->fetchAll();

                            if(!empty($comments)){

                                foreach ($comments as $comment){
                                    echo '<p>' .  $comment['Comment_Value'] . '</p>';
                                }
                            } else {
                                    echo 'There is no comment to show ';
                            }

                        ?>
                    </div>
                </div>

            </div>
        </div>
<?php
    }
    else{
        header('Location: login.php');
    }
     include $temp . "footer.php"; ?>
