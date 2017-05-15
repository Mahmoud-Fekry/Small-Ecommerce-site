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
                                        users.User_ID = items.Member_Id");
        
        //Execute the staement
        
        $stmt->execute();
        
        //Assign data to variable
        
        $rows = $stmt->fetchAll();
            
?>
        <div class="container">
            <h1 class="text-center">Manage Items</h1>
            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Description</td>
                        <td>Price</td>
                        <td>Added Date</td>
                        <td>Country Made</td>
                        <td>Category</td>
                        <td>Publisher</td>
                        <td>Control</td>
                    </tr>
                    <?php 
                        
                        foreach($rows as $row){
                            
                            echo '<tr>';
                                echo '<td>' . $row['Item_ID']          . '</td>';
                                echo '<td>' . $row['Item_Name']        . '</td>';
                                echo '<td>' . $row['Item_Description']     . '</td>';
                                echo '<td>' . $row['Item_Price']           . '</td>';
                                echo '<td>' . $row['Add_Date']         . '</td>';
                                echo '<td>' . $row['Country_Made']     . '</td>';
                                echo '<td>' . $row['category']           . '</td>';
                                echo '<td>' . $row['publisher']        . '</td>';
                            
                                echo '<td>';
                                            echo '<a href="?do=Edit&itemId=' . $row['Item_ID'] . '" class="btn btn-success">Edit</a> ';
                                            echo '<a href="?do=Delete&itemId=' . $row['Item_ID'] . '" class="btn btn-danger confirm">Delete</a> ';

                                            if($row['Item_Approve'] == 0){

                                                echo '<a href="items.php?do=Approve&ItemId=' . $row['Item_ID'] . '" class="btn btn-info">Approve</a> ';
                                            }
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>           
                </table>
            </div>
            
            <a class="btn btn-primary" href="?do=Add"><i class="fa fa-plus "></i> New Item</a>
        
        </div>
       
<?php        
                   
    }
    
    
    /***************************************************** Start Add Page *****************************************************/
    
    
    // If Get Reguest  do=Add go to Add Page
    
    else if($do == 'Add'){
        
?>
        
        <h1 class="text-center">Add Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    
                    <!-- Start Item Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Item Name</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="itemname"  required="required" />
                        </div> 
                    </div>

                    <!-- End Item Name field -->

                    <!-- Start Description field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-lable">Description</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="description" autocomplete="new-password"  required="required" />
                        </div> 
                    </div>

                    <!-- End description field -->

                    <!-- Start Price field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Price</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="price" required="required" />
                        </div> 
                    </div>

                    <!-- End Price field -->

                    <!-- Start Country field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Country</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="country" required="required" />
                        </div> 
                    </div>

                    <!-- End Country field -->
                    
                    <!-- Start Status field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Status</label>
                        <div class="col-sm-10">
                            <select name="status">
                                <option value="0">....</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="3">Old</option>
                            </select>
                        </div> 
                    </div>

                    <!-- End Status field -->
                    
                    <!-- Start Member field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Member</label>
                        <div class="col-sm-10">
                            <select name="member">
                                <option value="0">....</option>
                                <?php
                                    
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user){
                                        echo '<option value="' . $user['User_ID'] . '"> ' . $user['User_Name'] . '</option>';
                                    }
                                
                                ?>
                            </select>
                        </div> 
                    </div>

                    <!-- End Member field -->
                    
                    <!-- Start Category field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Category</label>
                        <div class="col-sm-10">
                            <select name="category">
                                <option value="0">....</option>
                                <?php
                                    
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $cats = $stmt->fetchAll();
                                    foreach($cats as $cat){
                                        echo '<option value="' . $cat['Cat_ID'] . '"> ' . $cat['Cat_Name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div> 
                    </div>

                    <!-- End Category field -->

                    <!-- Start Submit field -->

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value="Add"  />
                        </div> 
                    </div>

                    <!-- End Submit field -->
                </form>
            </div>      
    

<?php
    }
    
    
    /***************************************************** Start Insert Page *****************************************************/
    
    
    // If Get Reguest  do=Insert go to Insert Page
    
    else if($do == 'Insert'){
        
        //check if coming through Request Method or Directly using url
        
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
             echo '<h1 class="text-center"> Add New Item </h1>';
        
            echo '<div class="container">';
            
            //Get variable from form
    
            $itemName  = $_POST['itemname'];
            $des       = $_POST['description'];
            $price     = $_POST['price'];
            $country   = $_POST['country'];
            $status    = $_POST['status'];
            $member    = $_POST['member'];
            $category  = $_POST['category'];
            
            
            // Validate the form
            
            $formErrors = array();
            
            if(empty($itemName)){
                $formErrors[] = 'Name Can\'t Be Empty';
            }
            
            else{
                if(strlen($itemName) < 4){
                    $formErrors[] = 'Name Must Be more Than 4 Charactars';
                }
            }
            
            if(empty($des)){
                $formErrors[] = 'Description Can\'t Be Empty';
            }
            
            if(empty($price)){
                $formErrors[] = 'Price Can\'t Be Empty';
            }
            else{
                if(!is_numeric($price)){ $formErrors[] = 'Price must be number';}
                else{
                    if(strlen($price) > 7){ $formErrors[] = 'Price must be less than 7 degits';}
                }   
            }
            if(empty($country)){
                $formErrors[] = 'Country Can\'t Be Empty';
            }
            if($status == 0){
                $formErrors[] = 'Choose item status';
            }
            
            if($member == 0){
                $formErrors[] = 'Choose item\'s seller';
            }
            if($category == 0){
                $formErrors[] = 'Choose item\'s category';
            }
            
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            
            
            //Check if there is no error if true execute query
            
            if(empty($formErrors)){
                
                //Insert item info in database

                $stmt = $con->prepare("INSERT INTO items (Item_Name, Item_Description, Item_Price, Add_Date, Country_Made, Item_Status, Cat_Id, Member_Id)
                                                  VALUES (:iname, :ides, :iprice, now(), :icountry, :istatue, :icatid, :icatuser)");

                // Excute Query

                $stmt ->execute(array('iname' => $itemName, 'ides' => $des, 'iprice' => $price,
                                      'icountry' => $country,  'istatue' => $status,
                                      'icatid' => $category, 'icatuser' => $member));

                //Echo Sucess Message

                $msg = '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Item Added </div>';
                redirect($msg, 'back');
                echo '</div>';
            }
            
        }
        
        else{
            $msg = "<div class='alert alert-danger text-center'>You can't browse this page directly</div>";
            redirect($msg);
        }
        
    }
    
    
    /***************************************************** Start Edit Page *****************************************************/
    
    
    // If Get Reguest  do=Edit go to Edit Page  
    
    elseif ($do == 'Edit'){ //Edit page
        
        //Check if Get Request User ID numerical value && get it's value
        
        $itemId = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ? $_GET['itemId'] : 0;
        
        //Select all data depend on this ID
        
        $stmt = $con->prepare("SELECT * FROM items WHERE Item_ID=? LIMIT 1");
        
        //Excute Query
        
        $stmt-> execute(array($itemId));
        
        //Fetch The Data
        
        $items_rows = $stmt->fetch();
        
        //Calautae Number Of Records Has Found
        
        $count = $stmt->rowCount();
        
        // If Ther is Such ID Display The Form
        
        if($count > 0){            
?>
        
            <h1 class="text-center">Edit Item</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    
                    <!-- Start ID Field -->
                    
                    <input type="hidden" name="itemId" value="<?php echo $itemId ; ?>" />
                    
                    <!-- Start Item Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Item Name</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="itemname" value="<?php echo $items_rows['Item_Name']; ?>" required="required" />
                        </div> 
                    </div>

                    <!-- End Item Name field -->

                    <!-- Start Description field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-lable">Description</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="description" value="<?php echo $items_rows['Item_Description']; ?>"  required="required" />
                        </div> 
                    </div>

                    <!-- End description field -->

                    <!-- Start Price field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Price</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="price" value="<?php echo $items_rows['Item_Price']; ?>" required="required" />
                        </div> 
                    </div>

                    <!-- End Price field -->

                    <!-- Start Country field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Country</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="country" value="<?php echo $items_rows['Country_Made']; ?>" required="required" />
                        </div> 
                    </div>

                    <!-- End Country field -->
                    
                    <!-- Start Status field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Status</label>
                        <div class="col-sm-10">
                            <select name="status">
                                <option value="0">....</option>
                                <option value="1" <?php if($items_rows['Item_Status'] == 1) echo 'selected' ?>>New</option>
                                <option value="2" <?php if($items_rows['Item_Status'] == 2) echo 'selected' ?>>Like New</option>
                                <option value="3" <?php if($items_rows['Item_Status'] == 3) echo 'selected' ?>>Old</option>
                            </select>
                        </div> 
                    </div>

                    <!-- End Status field -->
                    
                    <!-- Start Member field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Member</label>
                        <div class="col-sm-10">
                            <select name="member">
                                <option value="0">....</option>
                                <?php
                                    
                                    $stmt = $con->prepare("SELECT * FROM users");
                                    $stmt->execute();
                                    $users = $stmt->fetchAll();
                                    foreach($users as $user){
                                        echo '<option value="' . $user['User_ID'] . '" ';
                                        if($items_rows['Member_Id'] == $user['User_ID']) echo 'selected';
                                        echo '> ' . $user['User_Name'] . '</option>';
                                    }
                                
                                ?>
                            </select>
                        </div> 
                    </div>

                    <!-- End Member field -->
                    
                    <!-- Start Category field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Category</label>
                        <div class="col-sm-10">
                            <select name="category">
                                <option value="0">....</option>
                                <?php
                                    
                                    $stmt = $con->prepare("SELECT * FROM categories");
                                    $stmt->execute();
                                    $cats = $stmt->fetchAll();
                                    foreach($cats as $cat){
                                        echo '<option value="' . $cat['Cat_ID'] . '" ';
                                        if($items_rows['Cat_Id'] == $cat['Cat_ID']) echo 'selected';
                                        echo '>' . $cat['Cat_Name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div> 
                    </div>

                    <!-- End Category field -->

                    <!-- Start Submit field -->

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value="Save"  />
                        </div> 
                    </div>

                    <!-- End Submit field -->
                </form>

<?php

            /******************************************** show comments on this item*******************************************************/
            //Select all items from database

            $stmt = $con->prepare("SELECT 
                                        comments.*,
                                        users.User_Name as user_name
                               FROM
                                        comments
                              INNER JOIN 
                                        users
                              ON 
                                        users.User_ID = comments.User_Id
                               WHERE 
                                        Item_Id=?");

            //Execute the staement

            $stmt->execute(array($itemId));

            //Assign data to variable

            $comments_rows = $stmt->fetchAll();

            if(!empty($comments_rows)){
                ?>
                <div class="container">
                    <h2 class="text-center" style="margin-top:70px">Comments</h2>
                    <div class="table-responsive">
                        <table class="main-table table table-bordered text-center">
                            <tr>
                                <td>Comment</td>
                                <td>User Name</td>
                                <td>Date</td>
                                <td>Control</td>
                            </tr>
                            <?php

                            foreach($comments_rows as $comment_row){

                                echo '<tr>';
                                echo '<td>' . $comment_row['Comment_Value']        . '</td>';
                                echo '<td>' . $comment_row['user_name']         . '</td>';
                                echo '<td>' . $comment_row['Comment_Date']         . '</td>';
                                echo '<td>';
                                echo '<a href="comments.php?do=Edit&commentId=' . $comment_row['Comment_ID'] . '" class="btn btn-success">Edit</a> ';
                                echo '<a href="comments.php?do=Delete&commentId=' . $comment_row['Comment_ID'] . '" class="btn btn-danger confirm">Delete</a> ';

                                if($comment_row['Comment_Status'] == 0){

                                    echo '<a href="comments.php?do=Approve&commentId=' . $comment_row['Comment_ID'] . '" class="btn btn-info">Approve</a> ';
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
            
            $itemId    = $_POST['itemId']; 
            $itemName  = $_POST['itemname'];
            $des       = $_POST['description'];
            $price     = $_POST['price'];
            $country   = $_POST['country'];
            $status    = $_POST['status'];
            $member    = $_POST['member'];
            $category  = $_POST['category'];
            
            
            // Validate the form
            
            $formErrors = array();
            
            if(empty($itemName)){
                $formErrors[] = 'Name Can\'t Be Empty';
            }
            
            else{
                if(strlen($itemName) < 4){
                    $formErrors[] = 'Name Must Be more Than 4 Charactars';
                }
            }
            
            if(empty($des)){
                $formErrors[] = 'Description Can\'t Be Empty';
            }
            
            if(empty($price)){
                $formErrors[] = 'Email Can\'t Be Empty';
            }
            else{
                if(!is_numeric($price)){ $formErrors[] = 'Price must be number';}
                else{
                    if(strlen($price) > 7){ $formErrors[] = 'Price must be less than 7 degits';}
                }   
            }
            if(empty($country)){
                $formErrors[] = 'Country Can\'t Be Empty';
            }
            if($status == 0){
                $formErrors[] = 'Choose item status';
            }
            
            if($member == 0){
                $formErrors[] = 'Choose item\'s seller';
            }
            if($category == 0){
                $formErrors[] = 'Choose item\'s category';
            }
            
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            
            
            //Check if there is no error if true execute query
            
            if(empty($formErrors)){
                //Update database with this data
            
                $stmt = $con->prepare("UPDATE 
                                            items 
                                       SET 
                                            Item_Name=?, Item_Description=?, Item_Price=?, Country_Made=?, Item_Status=?, Cat_Id=? 
                                       WHERE 
                                            Item_ID=?");
            
                // Excute Query
             
                $stmt ->execute(array($itemName, $des, $price, $country, $status, $category, $itemId));
                 
                //Echo Sucess Message
            
                $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Item Updated </div>';
                redirect($msg,'items');
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
        
        //Check if Get Request User ID numerical value && get it's value

        $itemId = isset($_GET['itemId']) && is_numeric($_GET['itemId']) ?intval($_GET['itemId']) : 0;
            
        echo '<h1 class="text-center">Delete Item</h1>';
        
        echo '<div class="container">';
            
        //Search about user in database
              
        $check = checkItem('Item_ID', 'items', $itemId);
            
        //Check if ther is user with such id
        
        if($check > 0){
            
            //Delte user from database
            
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID=?");
            
            // Excute Query
             
            $stmt ->execute(array($itemId));
                     
            //Echo Sucess Message
            
            $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Item Deleted </div>';
            redirect($msg, 'items');
            echo '</div>';
            
        }
        else{
            $msg = "<div class='alert alert-danger text-center'>There is no user with such id</div>";
            redirect($msg);
        }
        
    }

    /***************************************************** Start Approve Page *****************************************************/


    // If Get Reguest  do=Activate go to Delete Page

    elseif($do == 'Approve'){


        //Check if Get Request User ID numerical value && get it's value

        $itemId = isset($_GET['ItemId']) && is_numeric($_GET['ItemId']) ?intval($_GET['ItemId']) : 0;

        echo '<h1 class="text-center"> Approve Items  </h1>';

        echo '<div class="container">';

        //Search about user in database

        $check = checkItem('Item_ID', 'items', $itemId);

        //Check if ther is user with such id

        if($check > 0){

            //Delte user from database

            $stmt = $con->prepare("UPDATE items SET Item_Approve=? WHERE Item_ID=?");

            // Excute Query

            $stmt ->execute(array(1, $itemId));

            //Echo Sucess Message

            $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Item Approved </div>';
            redirect($msg, 'items');
            echo '</div>';

        }
        else{
            $msg = "<div class='alert alert-danger text-center'>There is no Item with such id</div>";
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
