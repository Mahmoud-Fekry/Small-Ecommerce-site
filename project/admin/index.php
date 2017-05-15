<?php 

session_start();
$noNavbar="";           //To prvent include Navbar page
$pageTitle = "Login";   //to show page title in title bar

if(isset($_SESSION['Username'])){
    header('location: dashboard.php');  //Redirect to dashboard page
}
include 'init.php';


//check if user coming from post request

if($_SERVER['REQUEST_METHOD']== 'POST'){
    
    $username = $_POST['user'];
    $password = $_POST['password'];
    $hashedPass = sha1($password);
    
    //check if user exist in database
    
    $stmt = $con->prepare("SELECT 
                                *
                           FROM 
                                users 
                           WHERE 
                                User_Name= ? 
                           AND 
                                Password= ? 
                           AND 
                                Group_ID= 1
                           LIMIT 1");
    $stmt ->execute(array($username,$hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    
    //if count > 0 that mean database contain recorde about this user
    
    if($count>0){
        $_SESSION['Username'] = $username;
        $_SESSION['UserId'] = $row['User_ID'];
        header('location: dashboard.php');
        exit();
    }
}
?>

    <form class="login text-center" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
        <h3>Admin Login</h3>
        <input class="form-control" type="text" name="user" placeholder="Username" autocomplete="off" />
        <input class="form-control" type="password" name="password" placeholder="Password" autocomplete="new-password" />
        <input class="btn btn-primary btn-block" type="submit" name="submit" value="Login" />
    </form>


    <?php include $temp . "footer.php"; ?>