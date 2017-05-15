<?php

    session_start();

    $pageTitle = "Item Details";

    include 'init.php';

    //check if there is request item (itemId) and itemId is numeric value

    $itemid = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? intval($_GET['itemId']) : 0;

    //Select all data depend on this ID

    $stmt = $con->prepare("SELECT 
                                            items.* ,
                                            categories.Cat_Name AS category,
                                            users.User_Name AS publisher
                                   FROM 
                                            items
                                  INNER JOIN 
                                            categories
                                  ON 
                                            categories.Cat_ID = items.Cat_Id
                                  INNER JOIN 
                                            users 
                                  ON        
                                            users.User_ID = items.Member_Id
                                  WHERE 
                                            Item_ID=?
                                  AND 
                                            Item_Approve = 1");

    //execute query

    $stmt -> execute(array($itemid));

    //count number of recordes

    $count = $stmt->rowCount();

    if($count > 0) {

        ///fetch all data if there is

        $item_Details = $stmt->fetch();

        ?>

        <h1 class="text-center"><?php echo $item_Details['Item_Name'] ?></h1>
        <div class="item-details">
            <div class="container">
                <div class="row">
                    <div class="col-md-3">
                        <div class="image-price text-center">
                            <div class="thumbnail">
                                <img class="img-responsive" src="layout/images/items/item.jpg" alt=""/>
                            </div>
                            <p>$<?php echo $item_Details['Item_Price'] ?></p>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="item-info">
                            <ul class="list-unstyled">
                                <li>
                                    <i class="fa fa-file-text fa-fw"></i>
                                    <span>Descripton</span> :
                                    <?php echo $item_Details['Item_Description'] ?>
                                </li>
                                <li>
                                    <i class="fa fa-star fa-fw"></i>
                                    <span>Status</span> :
                                    <?php
                                    if($item_Details['Item_Status'] == 1) echo 'New';
                                    elseif($item_Details['Item_Status'] == 2) echo 'Like New';
                                    else echo 'Old';
                                    ?>
                                </li>
                                <li>
                                    <i class="fa fa-building fa-fw"></i>
                                    <span>Country Made</span> :
                                    <?php echo $item_Details['Country_Made'] ?>
                                </li>
                                <li>
                                    <i class="fa fa-tags fa-fw"></i>
                                    <span>Category</span> :
                                    <a href="categories.php?pageId=<?php echo $item_Details['Cat_Id'] ?>&pageName=<?php echo $item_Details['category'] ?>"><?php echo $item_Details['category'] ?></a>
                                </li>
                                <li>
                                    <i class="fa fa-user fa-fw"></i>
                                    <span>Added By</span> :
                                    <?php echo $item_Details['publisher'] ?>
                                </li>
                                <li>
                                    <i class="fa fa-calendar fa-fw"></i>
                                    <span>Add Date</span> :
                                    <?php echo $item_Details['Add_Date'] ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ************************** Start comment section ************************** -->

                <hr class="custom-hr">
                <div class="row">
                    <div class="col-md-offset-3">
                        <div class="add-comment">
                            <h3>Add your comment</h3>

                            <?php
                                if(isset($_SESSION['user'])) { ?>

                            <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemId=' . $itemid ?>" method="post">
                                <textarea class="form-control" name="comment"></textarea>
                                <input class=" btn btn-primary" type="submit" value="Add your comment">
                            </form>

                            <?php

                            //check if comming from request method
                            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                                $comment = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
                                $userid = $_SESSION['uid'];

                                //insert comment in database if comment area not empty

                                if (!empty($comment)) {

                                    $stmt = $con->prepare("INSERT INTO comments(Comment_Value, Comment_Status, Comment_Date, Item_Id, User_Id)
                                                                   VALUES (:zcomment, '0', now(), :zitemid, :zuserid)");

                                    $stmt->execute(array(

                                        'zcomment' => $comment,
                                        'zitemid' => $itemid,
                                        'zuserid' => $userid

                                    ));

                                    //print success msg

                                    if ($stmt) {
                                        echo '<div class="alert alert-success text-center"> Comment Added </div>';
                                    }
                                }
                            }
                            ?>

                        </div>
                    </div>
                </div>

                <hr class="custom-hr">
                    <?php

                    //Display all comments for this item

                    $stmt = $con->prepare("SELECT 
                                                            comments.*,
                                                            users.User_Name AS member 
                                                    FROM 
                                                            comments
                                                   INNER JOIN
                                                            users 
                                                   ON 
                                                            users.User_ID = comments.User_Id");

                    //Execute query

                    $stmt->execute();

                    //asigin all data to variable

                    $comments = $stmt->fetchAll();

                    foreach ($comments as $com) {
                        ?>

                        <div class="comment-box">
                            <div class="row">
                                <div class="col-sm-2 text-center">
                                    <img class="img-responsive img-circle img-thumbnail center-block"
                                         src="layout/images/items/item.jpg" alt="">
                                    <?php echo $com['member'] ?>
                                </div>
                                <div class="col-sm-10">
                                    <p class="lead"><?php echo $com['Comment_Value'] ?></p>
                                </div>
                            </div>
                        </div>

                        <hr class="custom-hr">
                        <?php
                    }
                }else{
                    echo '<a href="login.php">Login </a>  to add comment';
                }
                    ?>
            </div>
        </div>


        <?php
    }else{
        echo '<div class="alert alert-danger text-center"> Sorry there is such item </div>';
    }


        ?>