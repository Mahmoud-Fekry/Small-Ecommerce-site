<?php

    session_start();
    $pageTitle="Login";
    if(isset($_SESSION['user'])){
        header('Location: profile.php');
    }
    include 'init.php';

    //check if user coming from http post requset

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_POST['login'])){
            $user = $_POST['username'];
            $pass = $_POST['password'];
            $hashedPass = sha1($pass);

            //check if user exist in database

            $stmt = $con->prepare("SELECT
                                        User_ID ,User_Name, Password 
                               FROM 
                                        users 
                               WHERE 
                                        User_Name=? 
                               AND 
                                        Password=?");

            $stmt->execute(array($user, $hashedPass));

            $get = $stmt->fetch();

            $count = $stmt->rowCount();

            //if count >0 that mean this user is exist in databae

            if($count > 0){
                $_SESSION['user'] = $user;          //store session name
                $_SESSION['uid'] = $get['User_ID'];          //store session id

                header('Location: index.php ');     //redirect to dashboard

                exit();
        }
        } else{

            $username       = $_POST['username'];
            $password1      = $_POST['password'];
            $password2      = $_POST['password-again'];
            $email          = $_POST['email'];
            $fullName       = $_POST['fullname'];

            $form_errors = array();

            if(isset($username)){
                $filtered_user = filter_var($username, FILTER_SANITIZE_STRING);

                //check if username length is between 4 and 32 characters

                if(strlen($filtered_user) > 32 || strlen($filtered_user) < 4){
                    $form_errors[] = 'Username must be between 4 - 32 characters.';
                }
            }

            //check if two entered passwords are equal

            if(isset($password1) && isset($password2)){

                //check if password field is empty or not

                if(empty($password1)){
                    $form_errors[] = 'Password can\'t be empty.';
                }

                $pass1 = sha1($password1);
                $pass2 = sha1($password2);

                if($pass1 !== $pass2){
                    $form_errors[] = 'Password don\'t match.';
                }
            }

            //make validation on email field

            if(isset($email)){

                $filtered_email = filter_var($email, FILTER_SANITIZE_EMAIL);

                if(filter_var($filtered_email, FILTER_VALIDATE_EMAIL) != true){
                    $form_errors[] = 'This email is not valid.';
                }
            }

            // check if user is exist in database

            $check = check_if_exist('User_Name', 'users', $filtered_user);

            if($check > 0){

                $form_errors[] = 'This user is already exist.';

            }

            // check if email is exist in database

            $check = check_if_exist('Email', 'users', $filtered_email);

            if($check > 0){

                $form_errors[] = 'This Email is already exist.';

            }

            //Add user to database if there is no errors

            if(empty($form_errors)){

                $insert_user = $con -> prepare("INSERT INTO users (User_Name, Password, Email, Full_Name, Reg_Status, User_Add_Date)
                                                      VALUES (:iuser, :ipass, :iemail, :iname, 0, now())");

                //execut query

                $insert_user ->execute(array('iuser' => $filtered_user, 'ipass' => $pass1, 'iemail' => $filtered_email, 'iname' => $fullName));

                //Echo Sucess Message

                $success_msg = 'Thank you for register please confirm your email';


            }
        }

    }

?>

    <div class="container login-page">
        <h1 class="text-center">
            <span class="active" data-class="login">Login</span> | <span data-class="sign-up">Sign Up</span>
        </h1>

        <!--********************************* Login form *********************************-->

        <form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter your username" required />
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter your password" required />
            <input class="btn btn-primary btn-block" type="submit" name="login" value="Login"/>
        </form>

        <!--********************************* Sign-up form *********************************-->

        <form class="sign-up" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <input class="form-control" type="text" name="username" autocomplete="off" placeholder="Enter your username" required />
            <input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Enter your password" required />
            <input class="form-control" type="password" name="password-again" autocomplete="new-password" placeholder="Enter your password again" required />
            <input class="form-control" type="email" name="email" placeholder="Enter your email" required/>
            <input class="form-control" type="text" name="fullname" autocomplete="off" placeholder="Enter your Full name" required />
            <input class="btn btn-success btn-block" type="submit" name="signup" value="Sign Up"/>
            <input class="alert alert-success btn-block" type="submit" name="signup" value="Sign Up"/>
        </form>

        <!-- *************************** Start errors section *************************** -->

        <div class="errors text-center">
            <?php

                if(!empty($form_errors)){
                    foreach ($form_errors as $error){
                        echo '<div class="error-msg text-center">' . $error . '</div>';
                    }
                }

                if(isset($success_msg))
                    echo '<div class="success-msg">' . $success_msg . '</div>';

            ?>
        </div>
    </div>


<?php include $temp . "footer.php"; ?>