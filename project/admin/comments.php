<?php

/*
                                            ==================================================
                                            ==               Manage member page             ==
                                            == You can Add / Edit /Delete member from here  ==
                                            ==================================================
*/

ob_start();
session_start();
if(isset($_SESSION['Username'])){
    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';


    /***************************************************** Start Manage Page *****************************************************/


    if($do == 'Manage'){

        //Select all items from database

        $stmt = $con->prepare("SELECT 
                                        comments.*,
                                        items.Item_Name as item_name,
                                        users.User_Name as user_name
                               FROM
                                        comments
                               INNER JOIN 
                                        items
                               ON   
                                        items.Item_ID = comments.Item_Id
                              INNER JOIN 
                                        users
                              ON 
                                        users.User_ID = comments.User_Id");

        //Execute the staement

        $stmt->execute();

        //Assign data to variable

        $rows = $stmt->fetchAll();

        ?>
        <div class="container">
            <h1 class="text-center">Manage Comments</h1>
            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>ID</td>
                        <td>Comment</td>
                        <td>Date</td>
                        <td>Item Name</td>
                        <td>User Name</td>
                        <td>Control</td>
                    </tr>
                    <?php

                    foreach($rows as $row){

                        echo '<tr>';
                        echo '<td>' . $row['Comment_ID']          . '</td>';
                        echo '<td>' . $row['Comment_Value']        . '</td>';
                        echo '<td>' . $row['Comment_Date']     . '</td>';
                        echo '<td>' . $row['item_name']           . '</td>';
                        echo '<td>' . $row['user_name']         . '</td>';
                        echo '<td>';
                        echo '<a href="comments.php?do=Edit&commentId=' . $row['Comment_ID'] . '" class="btn btn-success">Edit</a> ';
                        echo '<a href="comments.php?do=Delete&commentId=' . $row['Comment_ID'] . '" class="btn btn-danger confirm">Delete</a> ';

                        if($row['Comment_Status'] == 0){

                            echo '<a href="comments.php?do=Approve&commentId=' . $row['Comment_ID'] . '" class="btn btn-info">Approve</a> ';
                        }
                        echo '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>

        <?php

    }

    /***************************************************** Start Edit Page *****************************************************/


    // If Get Reguest  do=Edit go to Edit Page

    elseif ($do == 'Edit'){ //Edit page

        //Check if Get Request comment ID numerical value && get it's value

        $commentId = isset($_GET['commentId']) && is_numeric($_GET['commentId']) ? $_GET['commentId'] : 0;

        //Select all data depend on this ID

        $stmt = $con->prepare("SELECT * FROM comments WHERE comment_ID=? LIMIT 1");

        //Excute Query

        $stmt-> execute(array($commentId));

        //Fetch The Data

        $rows = $stmt->fetch();

        //Calautae Number Of Records Has Found

        $count = $stmt->rowCount();

        // If There is Such ID Display The Form

        if($count > 0){
            ?>

            <h1 class="text-center">Edit Comment</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">

                    <!-- Start ID Field -->

                    <input type="hidden" name="commentId" value="<?php echo $commentId ; ?>" />

                    <!-- Start Comment Value field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Comment</label>
                        <div class=" col-sm-10">
                            <textarea class="form-control" name="commentValue" required="required"><?php echo $rows['Comment_Value']; ?>
                            </textarea>
                        </div>
                    </div>

                    <!-- End Comment Value field -->

                    <!-- Start Submit field -->

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value="Save"  />
                        </div>
                    </div>

                    <!-- End Submit field -->
                </form>
            </div>
            <?php
        }
        // If Ther is No  Such ID Display Error Message

        else {

            $msg = "<div class='alert alert-danger text-center'>No there is such id</div>";
            redirect($msg);
        }

    }


    /***************************************************** Start Update Page *****************************************************/


    // If Get Reguest  do=Update go to Update Page

    elseif($do == 'Update'){

        //check if coming through Request Method or Directly using url

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            echo '<h1 class="text-center"> Update Item </h1>';

            echo '<div class="container">';

            $commentId    = $_POST['commentId'];
            $commentValue  = $_POST['commentValue'];

            // Validate the form

            $formErrors = array();

            if(empty($commentValue)){
                $formErrors[] = 'Comment Can\'t Be Empty';
            }

            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }


            //Check if there is no error if true execute query

            if(empty($formErrors)){
                //Update database with this data

                $stmt = $con->prepare("UPDATE 
                                            comments 
                                       SET 
                                            Comment_Value=? 
                                       WHERE 
                                            Comment_ID=?");

                // Excute Query

                $stmt ->execute(array($commentValue, $commentId));

                //Echo Sucess Message

                $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Item Updated </div>';
                redirect($msg,'comments');
                echo '</div>';
            }

        }

        else{
            $msg = "<div class='alert alert-danger text-center'>You can't browse this page directly</div>";
            redirect($msg);
        }
    }


    /***************************************************** Start Delete Page *****************************************************/


    // If Get Reguest  do=Delete go to Delete Page

    elseif($do == 'Delete'){

        //Check if Get Request Comment ID numerical value && get it's value

        $commentId = isset($_GET['commentId']) && is_numeric($_GET['commentId']) ?intval($_GET['commentId']) : 0;

        echo '<h1 class="text-center">Delete Comment</h1>';

        echo '<div class="container">';

        //Search about comment in database

        $check = checkItem('Comment_ID', 'comments', $commentId);

        //Check if ther is comment with such id

        if($check > 0){

            //Delte comment from database

            $stmt = $con->prepare("DELETE FROM comments WHERE Comment_ID=?");

            // Excute Query

            $stmt ->execute(array($commentId));

            //Echo Sucess Message

            $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Comment Deleted </div>';
            redirect($msg, 'back');
            echo '</div>';

        }
        else{
            $msg = "<div class='alert alert-danger text-center'>There is no comment with such id</div>";
            redirect($msg);
        }

    }

    /***************************************************** Start Approve Page *****************************************************/


    // If Get Reguest  do=Activate go to Delete Page

    elseif($do == 'Approve'){


        //Check if Get Request Comment ID numerical value && get it's value

        $commentId = isset($_GET['commentId']) && is_numeric($_GET['commentId']) ?intval($_GET['commentId']) : 0;

        echo '<h1 class="text-center"> Approve Items  </h1>';

        echo '<div class="container">';

        //Search about comment in database

        $check = checkItem('Comment_ID', 'comments', $commentId);

        //Check if ther is comment with such id

        if($check > 0){

            //Delte user from database

            $stmt = $con->prepare("UPDATE comments SET Comment_Status=? WHERE Comment_ID=?");

            // Excute Query

            $stmt ->execute(array(1, $commentId));

            //Echo Sucess Message

            $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Item Approved </div>';
            redirect($msg, 'back');
            echo '</div>';

        }
        else{
            $msg = "<div class='alert alert-danger text-center'>There is no Comment with such id</div>";
            redirect($msg);
        }
    }

    //Include Footer

    include $temp . "footer.php";
}

else{
    header('location: index.php');
    exit();
}

ob_end_flush();
?>
