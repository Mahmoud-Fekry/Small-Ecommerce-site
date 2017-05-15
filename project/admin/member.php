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
        
        // Add query variable to select pending mmebers only
        
        $query = '';
        
        //Check if get request page=pending
        
        if(isset($_GET['page']) && $_GET['page'] == 'pending'){
            
            $query = "AND Reg_Status = 0";
        }
        //Select all user from database except admins
        
        $stmt = $con->prepare("SELECT * FROM users WHERE Group_ID !=1 $query");
        
        //Execute the staement
        
        $stmt->execute();
        
        //Assign data to variable
        
        $rows = $stmt->fetchAll();
            
?>
        <div class="container">
            <h1 class="text-center">Manage Members</h1>
            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Registerd Date</td>
                        <td>Control</td>
                    </tr>
                    <?php 
                        
                        foreach($rows as $row){
                            
                            echo '<tr>';
                                echo '<td>' . $row['User_ID']    . '</td>';
                                echo '<td>' . $row['User_Name']  . '</td>';
                                echo '<td>' . $row['Email']     . '</td>';
                                echo '<td>' . $row['Full_Name']  . '</td>';
                                echo '<td>' . $row['User_Add_Date']      . '</td>';
                                echo '<td>' ;
                                     echo      '<a href="?do=Edit&UserId=' . $row['User_ID'] . '" class="btn btn-success">Edit</a> ';
                                     echo      '<a href="?do=Delete&UserId=' . $row['User_ID'] . '" class="btn btn-danger confirm">Delete</a> ';
                            
                                            if($row['Reg_Status'] == 0){
                                                
                                                echo '<a href="?do=Activate&UserId=' . $row['User_ID'] . '" class="btn btn-info">Activate</a> ';
                                            }
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>           
                </table>
            </div>
            
            <a class="btn btn-primary" href="?do=Add"><i class="fa fa-plus "></i> New Member</a>
        
        </div>
       
<?php            
    }
    
    
    /***************************************************** Start Add Page *****************************************************/
    
    
    // If Get Reguest  do=Add go to Add Page
    
    else if($do == 'Add'){
        
?>
        
        <h1 class="text-center">Add Members</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post" enctype="multipart/form-data">
                    
                    <!-- Start Username field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Username</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="username"  autocomplete="off"  required="required" />
                        </div> 
                    </div>

                    <!-- End Username field -->

                    <!-- Start Password field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-lable">Password</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="password" name="password" autocomplete="new-password"  required="required" />
                        </div> 
                    </div>

                    <!-- End Password field -->

                    <!-- Start Email field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Email</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" required="required" />
                        </div> 
                    </div>

                    <!-- End Email field -->

                    <!-- Start Full Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Full Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="fullname" required="required" />
                        </div> 
                    </div>

                    <!-- End Full Name field -->

                    <!-- Start image field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Profile Image</label>
                        <div class="col-sm-10">
                            <input type="file" name="profileimag" required="required" />
                        </div>
                    </div>

                    <!-- End image field -->

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
            
             echo '<h1 class="text-center"> Add New Member </h1>';
        
            echo '<div class="container">';
            
            //Get variable from form
    
            $username   = $_POST['username'];
            $pass       = $_POST['password'];
            $email      = $_POST['email'];
            $fullname   = $_POST['fullname'];
            
            //Password Trick
            
            $hashPass = sha1($pass);
            
            // Validate the form
            
            $formErrors = array();
            
            if(empty($username)){
                $formErrors[] = 'Username Can\'t Be Empty';
            }
            
            else{
                if(strlen($username) > 20){
                    $formErrors[] = 'Username Must Be Less Than 20 Charactar';
                }
            }
            
            if(empty($pass)){
                $formErrors[] = 'Password Can\'t Be Empty';
            }
            
            if(empty($email)){
                $formErrors[] = 'Email Can\'t Be Empty';
            }
            if(empty($fullname)){
                $formErrors[] = 'Full Name Can\'t Be Empty';
            }
            
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            
            
            //Check if there is no error if true execute query
            
            if(empty($formErrors)){
                
                $check = checkItem("User_Name", "users", $username);
                
                if($check > 0){
                    
                    $msg = "<div class='alert alert-danger text-center'>Sorry this user name is Existed</div>";
                    redirect($msg, 'back');
                }
                else{
                    //Insert user info in database

                    $stmt = $con->prepare("INSERT INTO users (User_Name, Password, Email, Full_Name, Reg_Status, User_Add_Date)
                                                      VALUES (:iuser, :ipass, :iemail, :iname, 1, now())");

                    // Excute Query

                    $stmt ->execute(array('iuser' => $username, 'ipass' => $hashPass, 'iemail' => $email, 'iname' => $fullname));

                    //Echo Sucess Message

                    $msg = '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Record Updated </div>';
                    redirect($msg, 'back');
                    echo '</div>';
                }
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

        $userId = isset($_GET['UserId']) && is_numeric($_GET['UserId']) ?intval($_GET['UserId']) : 0;
        
        //Select all data depend on this ID
        
        $stmt = $con->prepare("SELECT * FROM users WHERE User_ID=? LIMIT 1");
        
        //Excute Query
        
        $stmt ->execute(array($userId));
        
        //Fetch The Data
        
        $row = $stmt->fetch();
        
        //Calautae Number Of Records Has Found
        
        $count = $stmt->rowCount();
        
        // If Ther is Such ID Display The Form
        
        if($count>0){
?>
            <h1 class="text-center">Edit Member</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    
                    <!-- Start ID Field -->
                    
                    <input type="hidden" name="userid" value="<?php echo $userId ; ?>" />
                    
                    <!-- Start Username field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Username</label>
                        <div class=" col-sm-10">
                            <input class="form-control u" type="text" name="username" value="<?php echo $row['User_Name']; ?>" autocomplete="off"  required="required" />
                        </div> 
                    </div>

                    <!-- End Username field -->

                    <!-- Start Password field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-lable">Password</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="old-password" value="<?php echo $row['Password'] ?>" />
                            <input class="form-control" type="password" name="new-password" autocomplete="new-password" />
                        </div> 
                    </div>

                    <!-- End Password field -->

                    <!-- Start Email field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Email</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="email" name="email" value="<?php echo $row['Email']; ?>" required="required" />
                        </div> 
                    </div>

                    <!-- End Email field -->

                    <!-- Start Full Name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Full Name</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="fullname" value="<?php echo $row['Full_Name']; ?>" required="required" />
                        </div> 
                    </div>

                    <!-- End Full Name field -->

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
            
            echo '<h1 class="text-center"> Update Member </h1>';
        
            echo '<div class="container">';
            
            //Get variable from form
            
            $userid     = $_POST['userid'];
            $username   = $_POST['username'];
            $email      = $_POST['email'];
            $fullname   = $_POST['fullname'];
            
            //Password Trick
            
            $pass = empty($_POST['new-password']) ? $_POST['old-password'] : sha1($_POST['new-password'])  ;
            
            // Validate the form
            
            $formErrors = array();
            
            if(empty($username)){
                $formErrors[] = '<div class="alert alert-danger">Username Can\'t Be Empty</div>';
            }
            
            else{
                if(strlen($username) < 1 or strlen($username) > 20){
                    $formErrors[] = '<div class="alert alert-danger">Username Must Be Between 1 and 20 Charactar</div>';
                }
            }
            
            if(empty($email)){
                $formErrors[] = '<div class="alert alert-danger">Email Can\'t Be Empty</div>';
            }
            if(empty($fullname)){
                $formErrors[] = '<div class="alert alert-danger">Full Name Can\'t Be Empty</div>';
            }
            
            foreach($formErrors as $error) {
                echo $error;
            }
            
            
            //Check if there is no error if true execute query
            
            if(empty($formErrors)){

                //check if name user enterd existed in database excpet the name we currently edit
                $stmt2=$con->prepare("SELECT 
                                                * 
                                      FROM 
                                                users 
                                      WHERE 
                                                User_Name=? 
                                      AND 
                                                User_ID !=?");
                $stmt2->execute(array($username, $userid));
                $count = $stmt2->rowCount();
                if($count == 1){
                    $msg = "<div class='alert alert-danger text-center'>Sorry this user is existed</div>";
                    redirect($msg,'back');
                }
                else{

                    //Update database with this data

                    $stmt = $con->prepare("UPDATE users SET User_Name=? , Email=? , Full_Name=? , Password=? WHERE User_ID=?");

                    // Excute Query

                    $stmt ->execute(array($username, $email, $fullname, $pass, $userid));

                    //Echo Sucess Message

                    $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Record Updated </div>';
                    redirect($msg, 'members');
                    echo '</div>';
                }
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

        $userId = isset($_GET['UserId']) && is_numeric($_GET['UserId']) ?intval($_GET['UserId']) : 0;
            
        echo '<h1 class="text-center"> Delete Members </h1>';
        
        echo '<div class="container">';
            
        //Search about user in database
              
        $check = checkItem('User_ID', 'users', $userId);
            
        //Check if ther is user with such id
        
        if($check > 0){
            
            //Delte user from database
            
            $stmt = $con->prepare("DELETE FROM users WHERE User_ID=?");
            
            // Excute Query
             
            $stmt ->execute(array($userId));

            //Echo Sucess Message
            
            $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' User Deleted </div>';
            redirect($msg, 'members');
            echo '</div>';
            
        }
        else{
            $msg = "<div class='alert alert-danger text-center'>There is no user with such id</div>";
            redirect($msg);
        }
    }    
    
    /***************************************************** Start Activate Page *****************************************************/
    
    
    // If Get Reguest  do=Activate go to Delete Page
    
    elseif($do == 'Activate'){
        
        
        //Check if Get Request User ID numerical value && get it's value

        $userId = isset($_GET['UserId']) && is_numeric($_GET['UserId']) ?intval($_GET['UserId']) : 0;
            
        echo '<h1 class="text-center"> Active Members </h1>';
        
        echo '<div class="container">';
            
        //Search about user in database
              
        $check = checkItem('User_ID', 'users', $userId);
            
        //Check if ther is user with such id
        
        if($check > 0){
            
            //Delte user from database
            
            $stmt = $con->prepare("UPDATE users SET Reg_Status=? WHERE User_ID=?");
            
            // Excute Query
             
            $stmt ->execute(array(1, $userId));
                     
            //Echo Sucess Message
            
            $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' User Activated </div>';
            redirect($msg, 'members');
            echo '</div>';
            
        }
        else{
            $msg = "<div class='alert alert-danger text-center'>There is no user with such id</div>";
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
