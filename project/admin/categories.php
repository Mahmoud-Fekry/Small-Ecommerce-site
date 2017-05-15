<?php

/*
                                            ==================================================
                                            ==               Manage Category page             ==
                                            == You can Add / Edit /Delete category from here  ==
                                            ==================================================
*/

ob_start();

session_start();

$pageTitle = '';

if(isset($_SESSION['Username'])){
    include 'init.php';
    
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    
    
    /***************************************************** Start Manage Page *****************************************************/
    
    
    if($do == 'Manage'){ 
        
        
        $sort = 'ASC';
        
        $sort_array = array('ASC', 'DESC');
        
        if(isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {
            $sort = $_GET['sort'];
        }
         //Select all user from database except admins
        
        $stmt = $con->prepare("SELECT * FROM categories ORDER BY Cat_Ordering $sort");
        
        //Execute the staement
        
        $stmt->execute();
        
        //Assign data to variable
        
        $rows = $stmt->fetchAll();
            
?>
        <div class="container">
            <h1 class="text-center">Manage Categories</h1>
            <div class="table-responsive">
                <table class="main-table table table-bordered text-center">
                    <tr>
                        <td>ID</td>
                        <td>Name</td>
                        <td>Ordering</td>
                        <td>Visibility</td>
                        <td>Commenting</td>
                        <td>Ads</td>
                        <td>Description</td>
                        <td>Control</td>
                    </tr>
                    <?php 
                        
                        foreach($rows as $row){
                            
                            echo '<tr>';
                                echo '<td>' . $row['Cat_ID']                     . '</td>';
                                echo '<td>' . $row['Cat_Name']                   . '</td>';
                                echo '<td>' . $row['Cat_Ordering']               . '</td>';
                                echo '<td>';
                                            if($row['Cat_Visibility'] == 0){
                                                echo '<span class="btn btn-warning"> Not Visible </span>';
                                            }
                                            else{
                                                echo '<span class="btn btn-info"> Visible </span>';
                                            }
                                echo '</td>';
                                echo '<td>';
                                            if($row['Allow_Comment'] == 0){
                                                echo '<span class="btn btn-warning"> Not Allowed </span>';
                                            }
                                            else{
                                                echo '<span class="btn btn-info"> Allowed </span>';
                                            }
                                echo '</td>';
                                echo '<td>';
                                            if($row['Allow_Ads'] == 0){
                                                echo '<span class="btn btn-warning"> Not Allowed </span>';
                                            }
                                            else{
                                                echo '<span class="btn btn-info"> Allowed </span>';
                                            }
                            
                                            
                                         
                                echo '</td>';
                                echo '<td>';
                                            if(empty($row['Cat_Description'])) {echo 'No Description';}
                                            else {echo $row['Cat_Description'];}
                                echo '</td>';
                            
                                echo '<td>
                                            <a href="categories.php?do=Edit&id=' . $row['Cat_ID'] . '" class="btn btn-success">Edit</a>
                                            <a href="categories.php?do=Delete&id=' . $row['Cat_ID'] . '" class="btn btn-danger confirm">Delete</a>';
                                echo '</td>';
                            echo '</tr>';
                        }
                    ?>           
                </table>
            </div>
            
            <a class="btn btn-primary" href="categories.php?do=Add"><i class="fa fa-plus "></i> New Category</a>
            <div class="order pull-right">
                <span>Order: </span>
                <a class="<?php if($sort == 'ASC'){echo 'active';} ?>" href="categories.php?do=Manage&sort=ASC"> ASC </a>
                |      <a class="<?php if($sort == 'DESC'){echo 'active';} ?>" href="categories.php?do=Manage&sort=DESC"> DESC </a>
            </div>
        
        </div>
       
<?php
        
    }
    
    
    /***************************************************** Start Add Page *****************************************************/
    
    
    // If Get Reguest  do=Add go to Add Page
    
    else if($do == 'Add'){
        
?>
        
        <h1 class="text-center">Add Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Insert" method="post">
                    
                    <!-- Start Category name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Category Name</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="categoryName"  autocomplete="off" required="required" />
                        </div> 
                    </div>

                    <!-- End Category name field -->

                    <!-- Start Category description field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-lable">Description</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="descreption" autocomplete="off" />
                        </div> 
                    </div>

                    <!-- End Category description field -->

                    <!-- Start Category ordering field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Ordering</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="ordering" required="required" />
                        </div> 
                    </div>

                    <!-- End Category ordering field -->

                    <!-- Start Visablity field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Category parent</label>
                        <div class="col-sm-10">
                            <select name="parent">
                                <option value="0">None</option>
                                <?php

                                $catParent = $con->prepare("SELECT * FROM categories WHERE Cat_Parent = ?");
                                $catParent->execute(array(0));
                                $parent = $catParent->fetchAll();
                                foreach($parent as $p){
                                    echo '<option value="' . $p['Cat_ID'] . '"> ' . $p['Cat_Name'] . '</option>';
                                }

                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- End Visablity field -->

                    <!-- Start Visablity field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Visable</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="visible-yes" type="radio" name="visiblity" value="1" checked/>
                                <label for="visible-yes">Yes</label>
                            </div>
                            <div>
                                <input id="visible-no" type="radio" name="visiblity" value="0" />
                                <label for="visible-no">No</label>
                            </div>
                        </div> 
                    </div>

                    <!-- End Visablity field -->
                    
                    <!-- Start Commenting field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Allow Commenting</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="comment-yes" type="radio" name="commenting" value="1" checked/>
                                <label for="comment-yes">Yes</label>
                            </div>
                            <div>
                                <input id="comment-no" type="radio" name="commenting" value="0" />
                                <label for="comment-no">No</label>
                            </div>
                        </div> 
                    </div>

                    <!-- End Commenting field -->
                    
                    <!-- Start Ads field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Allow Ads</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="1" checked />
                                <label for="ads-yes">Yes</label>
                            </div>
                            <div>
                                <input id="ads-no" type="radio" name="ads" value="0"/>
                                <label for="ads-no">No</label>
                            </div>
                        </div> 
                    </div>

                    <!-- End Ads field -->

                    <!-- Start Submit field -->

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value="Add Category" />
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
    
            $name           = $_POST['categoryName'];
            $description    = $_POST['descreption'];
            $order          = $_POST['ordering'];
            $parent         = $_POST['parent'];
            $visible        = $_POST['visiblity'];
            $comment        = $_POST['commenting'];
            $ads            = $_POST['ads'];
            
            
            // Validate the form
            
            $formErrors = array();
            
            if(empty($name)){
                $formErrors[] = '>Name Can\'t Be Empty';
            }
            else{
                $check = checkItem('Cat_Name', 'categories', $name);
                if($check > 0){ $formErrors[] = 'This Name is not avaliable';}
            }
            if(empty($order)){
                $formErrors[] = 'Ordering Can\'t Be Empty';
            }
            else{
                if(is_numeric($order)){
                    $check = checkItem('Cat_Ordering', 'categories', $order);
                    if($check > 0){ $formErrors[] = 'This Ordering number is not avaliable';}
                }
                else{
                    $formErrors[] = 'Ordering Must Be Number';
                }
            }
            

            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            
            //Check if there is no error if true execute query
            
            if(empty($formErrors)){
                
                $check = checkItem("Cat_Name", "categories", $name);
                
                if($check > 0){
                    
                    $msg = "<div class='alert alert-danger text-center'>Sorry this name is Existed</div>";
                    redirect($msg, 'back');
                }
                else{
                    //Insert user info in database

                    $stmt = $con->prepare("INSERT INTO categories (Cat_Name, Cat_Description, Cat_Ordering, Cat_Parent, Cat_Visibility, Allow_Comment, Allow_Ads)
                                                      VALUES (:iname, :ides, :iorder, :iparent, :ivisible, :icomment, :iads)");

                    // Excute Query

                    $stmt ->execute(array(  'iname'     => $name,
                                            'ides'      => $description,
                                            'iorder'    => $order,
                                            'iparent'   => $parent,
                                            'ivisible'  => $visible,
                                            'icomment'  => $comment,
                                            'iads'      => $ads));

                    //Echo Sucess Message

                    $msg = '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Category Added </div>';
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
    
    elseif ($do == 'Edit'){ 
        
        //Check if Get Request ID numerical value && get it's value
        
        $id = isset($_GET['id']) && is_numeric($_GET['id']) ? intval($_GET['id']) : 0;
        
        // Select all data depend on this id
        
        $stmt = $con->prepare("SELECT * FROM categories WHERE Cat_ID=?");
        
        // Excute Query
        
        $stmt->execute(array($id));
        
        // Fetch data
        
        $rows = $stmt->fetch();
        
        // Calaculate number of result fount
        
        $count = $stmt -> rowCount();
        
        // If there is such id dislay form
        
        if($count > 0){
?>
            <h1 class="text-center">Edit Category</h1>
            <div class="container">
                <form class="form-horizontal" action="?do=Update" method="post">
                    
                    <!-- Start ID Field -->
                    
                    <input type="hidden" name="categoryId" value="<?php echo $id ; ?>" />
                    
                    <!-- Start Category name field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Category Name</label>
                        <div class=" col-sm-10">
                            <input class="form-control" type="text" name="categoryName" value="<?php echo $rows['Cat_Name']; ?>" autocomplete="off"  requird="required" />
                        </div> 
                    </div>

                    <!-- End Category name field -->

                    <!-- Start Category description field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2  control-lable">Description</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="descreption" value="<?php echo $rows['Cat_Description']; ?>" autocomplete="off" />
                        </div> 
                    </div>

                    <!-- End Category description field -->

                    <!-- Start Category ordering field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Ordering</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="ordering" value="<?php echo $rows['Cat_Ordering']; ?>" />
                        </div> 
                    </div>

                    <!-- End Category ordering field -->

                    <!-- Start Visablity field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Visable</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="visible-yes" type="radio" name="visiblity" value="1"
                                       <?php if($rows['Cat_Visibility'] ==1) echo 'checked'; ?> />
                                <label class="category-yes" for="visible-yes">Yes</label>                            
                                <input id="visible-no" type="radio" name="visiblity" value="0"
                                       <?php if($rows['Cat_Visibility'] == 0) echo 'checked'; ?>/>
                                <label for="visible-no">No</label>
                            </div>
                        </div> 
                    </div>

                    <!-- End Visablity field -->
                    
                    <!-- Start Commenting field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Allow Commenting</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="comment-yes" type="radio" name="commenting" value="1"
                                       <?php if($rows['Allow_Comment'] == 1) echo 'checked'; ?> />
                                <label class="category-yes" for="comment-yes">Yes</label>
                                <input id="comment-no" type="radio" name="commenting" value="0"
                                       <?php if($rows['Allow_Comment'] == 0) echo 'checked'; ?>/>
                                <label for="comment-no">No</label>
                            </div>
                        </div> 
                    </div>

                    <!-- End Commenting field -->
                    
                    <!-- Start Ads field -->

                    <div class="form-group form-group-lg">
                        <label class="col-sm-2 control-lable">Allow Ads</label>
                        <div class="col-sm-10">
                            <div>
                                <input id="ads-yes" type="radio" name="ads" value="1"
                                       <?php if($rows['Allow_Ads'] == 1) echo 'checked'; ?> />
                                <label class="category-yes" for="ads-yes">Yes</label>
                                <input id="ads-no" type="radio" name="ads" value="0"
                                       <?php if($rows['Allow_Ads'] == 0) echo 'checked'; ?> />
                                <label for="ads-no">No</label>
                            </div>
                        </div> 
                    </div>

                    <!-- End Ads field -->

                    <!-- Start Submit field -->

                    <div class="form-group form-group-lg">
                        <div class="col-sm-offset-2 col-sm-10">
                            <input class="btn btn-primary" type="submit" value="Update Category" />
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
            
            echo '<h1 class="text-center"> Update Category </h1>';
        
            echo '<div class="container">';
            
            //Get variable from form
            
            $id             = $_POST['categoryId'];
            $name           = $_POST['categoryName'];
            $description    = $_POST['descreption'];
            $order          = $_POST['ordering'];
            $visible        = $_POST['visiblity'];
            $comment        = $_POST['commenting'];
            $ads            = $_POST['ads'];
            
            
             // Validate the form
            
           $formErrors = array();
            
            if(empty($name)){
                $formErrors[] = 'Name Can\'t Be Empty';
            }
            if(empty($order)){
                $formErrors[] = 'Ordering Can\'t Be Empty';
            }
            else {
                if (!is_numeric($order)) {
                    $formErrors[] = 'Ordering Must Be Number';
                }
            }
            foreach($formErrors as $error) {
                echo '<div class="alert alert-danger">' . $error . '</div>';
            }
            
            
            //Check if there is no error if true execute query
            
            if(empty($formErrors)){

                //check if name user enterd existed in database excpet the name we currently edit
                $stmt2=$con->prepare("SELECT 
                                                * 
                                      FROM 
                                                categories 
                                      WHERE 
                                                Cat_Name=? 
                                      AND 
                                                Cat_ID !=?");
                $stmt2->execute(array($name, $id));
                $count = $stmt2->rowCount();
                if($count == 1){
                    $msg = "<div class='alert alert-danger text-center'>Sorry this name is existed</div>";
                    redirect($msg,'back');
                }
                else{
                    //check if ordering user enterd existed in database excpet the ordering we currently edit
                    $stmt2=$con->prepare("SELECT 
                                                * 
                                      FROM 
                                                categories 
                                      WHERE 
                                                Cat_Ordering=? 
                                      AND 
                                                Cat_ID !=?");
                    $stmt2->execute(array($order, $id));
                    $count = $stmt2->rowCount();
                    if($count == 1){
                        $msg = "<div class='alert alert-danger text-center'>Sorry this Ordering is not avaliable</div>";
                        redirect($msg,'back');
                    }
                    else{

                        //Update database with this data

                        $stmt = $con->prepare("UPDATE 
                                                categories  
                                       SET 
                                                Cat_Name=?, Cat_Description=?, Cat_Ordering=?, Cat_Visibility=?, Allow_Comment=?, Allow_Ads=?
                                       WHERE 
                                                Cat_ID=? ");

                        // Excute Query

                        $stmt ->execute(array($name, $description, $order, $visible, $comment, $ads, $id));

                        //Echo Sucess Message

                        $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Category Updated </div>';
                        redirect($msg, 'categories');
                        echo '</div>';
                    }
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

        $id = isset($_GET['id']) && is_numeric($_GET['id']) ?intval($_GET['id']) : 0;
            
        echo '<h1 class="text-center"> Delete Category </h1>';
        
        echo '<div class="container">';
            
        //Search about category in database
              
        $check = checkItem('Cat_ID', 'categories', $id);
            
        //Check if ther is category with such id
        
        if($check > 0){
            
            //Delte Category from database
            
            $stmt = $con->prepare("DELETE FROM categories WHERE Cat_ID=?");
            
            // Excute Query
             
            $stmt ->execute(array($id));
                     
            //Echo Sucess Message
            
            $msg =  '<div class="alert alert-success text-center">' . $stmt->rowCount() . ' Category Deleted </div>';
            redirect($msg, 'categories');
            echo '</div>';
            
        }
        else{
            $msg = "<div class='alert alert-danger text-center'>There is no category with such id</div>";
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
