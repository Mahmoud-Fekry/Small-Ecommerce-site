<?php

    session_start();
    include 'init.php';
?>

<div class="container">
    <div class="row">
        <?php

            //get all item from items table  and store it in $all_items

            $all_items = getAllFrom('items', 'Item_ID');

            foreach ($all_items as $item){ ?>
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail item-box">
                        <span class="item-price"><?php echo '$' . $item['Item_Price']; ?></span>
                        <img class="img-responsive" src="layout/images/items/item.jpg" alt=""/>
                        <div class="caption">
                            <h3><a href="items.php?itemId=<?php echo $item['Item_ID'] ?>"><?php echo $item['Item_Name']; ?></a></h3>
                            <p class="description"><?php echo $item['Item_Description']; ?></p>
                            <span class="add-date"><?php echo $item['Add_Date'] ?></span>
                        </div>
                    </div>
                </div>
            <?php   }
        ?>
    </div>
</div>


<?php include $temp . "footer.php"; ?>