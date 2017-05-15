<?php





/*
**
*** getAllFrom function v1.0
*** This function get all data from table
**
*/


function getAllFrom($tableName, $orderBy){
    global $con;

    $getAll = $con->prepare("Select  *  From  $tableName ORDER BY $orderBy DESC");

    $getAll->execute();

    $all = $getAll->fetchAll();

    return $all;
}


/*
**
*** getItem function v2.0
*** This function get Items from Database
*** This function Accept parameters
*** $where = the column we choose throw
*** $value = the value of $where
**
*/


function getItem($where, $value, $approve = null)
{
    global $con;

    $sql = $approve == null ? 'AND Item_Approve = 1' : '';

    $getItem = $con->prepare("Select  *  From  items WHERE $where =? $sql ORDER BY Item_ID DESC");

    $getItem->execute(array($value));

    $items = $getItem->fetchAll();

    return $items;
}


/*
**
*** check user status function v1.0
*** This function to check if user is activated by admin or not
*** This function Accept parameters
*** $user = the username of user
**
*/

function checkUserStatus($user){
    global $con;

    $userStatus = $con->prepare("SELECT User_Name, Reg_Status FROM users WHERE User_Name=? AND Reg_Status=0");

    $userStatus->execute(array($user));

    $userNum=$userStatus->rowCount();

    return $userNum;
}


/*
**
*** check_if_exist function v1.0
*** This function check if item exist in database [this function accept parameters]
*** $item = The item to select
*** $table = the table to select from
*** $value = the value of $select
**
*/


function check_if_exist($item, $table, $value){
    global $con;

    $checkIfExist = $con->prepare("Select  $item  From  $table  WHERE $item=? ");

    $checkIfExist->execute(array($value));

    $checkResult = $checkIfExist->rowCount();

    return $checkResult;
}






































/*
**
*** getTitle function v1.0
*** Title function that show page title if the page have variable $pageTitle and show default title for other pages
**
*/

function getTitle() {
    
    global $pageTitle;
    if(isset($pageTitle)){
        echo $pageTitle;
    } else {
        echo 'Default';
    }
}


/*
**
*** redirect function v3.0
*** This funnction redirect the user to home page
*** redirect[this function accept parameters]
*** $Msg = echo the error message
*** $url = the link you want to dirict to
*** $seconds = seconds before redirect
**
*/

function redirect($msg, $url = null, $seconds = 3){
    
    if($url === null ){
        $url = 'dashboard.php';
        $link = 'Dashboard Page';
    }
    else if($url === 'categories' ){
        $url = 'categories.php';
        $link = 'Categoris Page';
    }
    else if($url === 'members' ){
        $url = 'member.php';
        $link = 'Members Page';
    }
    else if($url === 'items' ){
        $url = 'items.php';
        $link = 'Items Page';
    }
    else if($url === 'comments' ){
        $url = 'comments.php';
        $link = 'Comments Page';
    }
    else{
        $url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
        $link = 'Previous Page';
    }
    
    echo $msg;
    echo '<div class="text-center">You Will Be Direct To ' . $link . ' After ' . $seconds . ' Seconds.';
    
    header('refresh:' . $seconds . ';url=' . $url . '');
}

/*
**
*** checkItem function v1.0
*** This function check if item exist in database [this function accept parameters]
*** $item = The item to select 
*** $table = the table to select from
*** $value = the value of $select
**
*/


function checkItem($item, $table, $value){
    global $con;
    
    $stmt2 = $con->prepare("Select  $item  From  $table  WHERE $item=? ");
    
    $stmt2->execute(array($value));
    
    $resultNum = $stmt2->rowCount();
    
    return $resultNum;
}

/*
**
*** countItem function v2.0
*** This function count number of item in table [this function accept parameters]
*** $item = The item to count 
*** $table = the table to count from
*** $condition = condition to select spesific recorde
**
*/


function countItem($item, $table, $condition = null){
    
    global $con;
    
    if($condition === null){
        $stmt3 = $con->prepare("SELECT COUNT($item) FROM $table");
        $stmt3->execute();
    }
    else{
        $stmt3 = $con->prepare("SELECT COUNT($item) FROM $table WHERE $item=?");
        $stmt3->execute(array($condition));
    } 
    
    return $stmt3->fetchColumn();
}

/*
**
*** getLatest function v1.0
*** This function return latest items or users [this function accept parameters]
*** $select = The item to count 
*** $table = the table to display from
*** $order = the way to order by
*** $limit = number of items or users that will be disolay 
**
*/


function getLatest($select, $table, $order, $limit = 4){
    
    global $con;
    
    $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
    
    $getStmt->execute();
    
    $rows = $getStmt->fetchAll();
    
    return $rows;
}