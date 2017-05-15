<?php

    session_start();

    $pageTitle = "Create New Item";

    include 'init.php';

    if(isset($_SESSION['user'])){

       if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $formErrors = array();

            $item_name          = filter_var($_POST['itemname'], FILTER_SANITIZE_STRING);
            $item_des           = filter_var($_POST['itemdes'], FILTER_SANITIZE_STRING);
            $item_price         = filter_var($_POST['itemprice'], FILTER_SANITIZE_NUMBER_INT);
            $item_country       = filter_var($_POST['itemcountry'], FILTER_SANITIZE_STRING);
            $item_status        = filter_var($_POST['itemstatus'], FILTER_SANITIZE_NUMBER_INT);
            $item_category      = filter_var($_POST['itemcategory'], FILTER_SANITIZE_NUMBER_INT);
            // Start validation

            if(empty($item_name)){
                $formErrors[] = 'Name Can\'t Be Empty';
            }

            else{
                if(strlen($item_name) < 4){
                    $formErrors[] = 'Name Must Be more Than 4 Charactars';
                }
            }

            if(empty($item_des)){
                $formErrors[] = 'Description Can\'t Be Empty';
            }

            if(empty($item_price)){
                $formErrors[] = 'Price Can\'t Be Empty';
            }
            else{
                if(strlen($item_price) > 7){ $formErrors[] = 'Price must be less than 7 degits';}
            }
            if(empty($item_country)){
                $formErrors[] = 'Country Can\'t Be Empty';
            }
            if($item_status == 0){
                $formErrors[] = 'Choose item\'s status';
            }
            if($item_category == 0){
                $formErrors[] = 'Choose item\'s category';
            }

        }
?>

    <!--******************* create new ads *******************-->

    <h1 class="text-center"><?php echo $pageTitle; ?></h1>
    <div class="new-ads block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading "><?php echo $pageTitle; ?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">

                                <!-- Start Item Name field -->

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-lable">Item Name</label>
                                    <div class=" col-sm-10 col-md-8">
                                        <input class="form-control live" data-class=".live-name" type="text" name="itemname" value="<?php if(isset($item_name)) echo $item_name?>" required />
                                    </div>
                                </div>

                                <!-- End Item Name field -->

                                <!-- Start Description field -->

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2  control-lable">Description</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input class="form-control live" data-class=".live-des" type="text" name="itemdes" value="<?php if(isset($item_des))echo $item_des?>" required >
                                    </div>
                                </div>

                                <!-- End description field -->

                                <!-- Start Price field -->

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-lable">Price</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input class="form-control live" data-class=".live-price" type="text" name="itemprice" value="<?php if(isset($item_price)) echo $item_price?>" required />
                                    </div>
                                </div>

                                <!-- End Price field -->

                                <!-- Start Country field -->

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-lable">Country</label>
                                    <div class="col-sm-10 col-md-8">
                                        <input class="form-control" type="text" name="itemcountry" value="<?php if(isset($item_country)) echo $item_country?>" required />
                                    </div>
                                </div>

                                <!-- End Country field -->

                                <!-- Start Status field -->

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-lable">Status</label>
                                    <div class="col-sm-10 col-md-8">
                                        <select name="itemstatus" required>
                                            <option value="0">....</option>
                                            <option value="1">New</option>
                                            <option value="2">Like New</option>
                                            <option value="3">Old</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- End Status field -->

                                <!-- Start Category field -->

                                <div class="form-group form-group-lg">
                                    <label class="col-sm-2 control-lable">Category</label>
                                    <div class="col-sm-10 col-md-8">
                                        <select name="itemcategory">
                                            <option value="0">....</option>
                                            <?php


                                            $cats = getAllFrom('categories', 'Cat_ID');

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
                        <div class="col-md-4">
                            <div class="thumbnail item-box live-preview">
                                <span class="item-price">$
                                    <span class="live-price">0</span>
                                </span>
                                <img class="img-responsive" src="layout/images/items/item.jpg" alt=""/>
                                <div class="caption">
                                    <h3 class="live-name">test</h3>
                                    <p class="live-des">test</p>
                                </div>
                            </div>

                        </div>
                    </div>


                    <?php

                    // Start error section

                    if(!empty($formErrors)){

                        foreach ($formErrors as $error){

                            echo '<div class="alert alert-danger text-center">' . $error  . '</div>';
                        }

                    }

                    // end error section

                    // Add item in database

                    else {

                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                            //Insert item info in database

                            $stmt = $con->prepare("INSERT INTO items (Item_Name, Item_Description, Item_Price, Add_Date, Country_Made, Item_Status, Cat_Id, Member_Id)
                                                  VALUES          (:iname, :ides, :iprice, now(), :icountry, :istatue, :icatid, :icatuser)");

                            // Excute Query

                            $stmt->execute(array('iname' => $item_name, 'ides' => $item_des, 'iprice' => $item_price,
                                'icountry' => $item_country, 'istatue' => $item_status,
                                'icatid' => $item_category, 'icatuser' => $_SESSION['uid']));

                            //Echo Sucess Message

                            echo '<div class="alert alert-success text-center"> Item Added Succssefully </div>';
                        }
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
    include $temp . "footer.php";
?>
