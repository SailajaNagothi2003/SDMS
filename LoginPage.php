<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Function to log user activities
function logUserActivity($dbh, $email, $uip, $status, $username, $studentid, $fname, $lname) {
    $sql = "INSERT INTO userlog(userEmail, userip, status, username, studentid, name, lastname) 
            VALUES(:email, :uip, :status, :username, :studentid, :fname, :lname)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':studentid', $studentid, PDO::PARAM_STR);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':lname', $lname, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':uip', $uip, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    
    $sql = "SELECT * FROM students WHERE email = :email AND password = :password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);

    if ($query->rowCount() > 0) {
        foreach ($results as $result) {
        $_SESSION['sid'] = $result->studentid;
        $_SESSION['username'] = $result->username;
        $_SESSION['fname'] = $result->fname;
        $_SESSION['lname'] = $result->lname;
        $_SESSION['role'] = $result->user;
        $_SESSION['email'] = $result->email;
        $_SESSION['spec'] = $result->spec;
        $_SESSION['first_login'] = $result->first_login;
        }
        if (!empty($_POST["remember"])) {
            setcookie("user_login", $_POST["email"], time() + (10 * 365 * 24 * 60 * 60));
            setcookie("userpassword", $_POST["password"], time() + (10 * 365 * 24 * 60 * 60));
        } else {
            if (isset($_COOKIE["user_login"])) {
                setcookie("user_login", "", time() - 3600);
                if (isset($_COOKIE["userpassword"])) {
                    setcookie("userpassword", "", time() - 3600);
                }
            }
        }

        if ($result->status == "1") {
            if ($_SESSION['first_login'] == 1) {
                $sql = "UPDATE students SET first_login = 0 WHERE studentid = :sid";
                $query = $dbh->prepare($sql);
                $query->bindParam(':sid', $_SESSION['sid'], PDO::PARAM_STR);
                $query->execute();
                $_SESSION['first_login'] = 0;
                $redirectPage = "WelcomePage.php";
            } else {
                $redirectPage = "MainPage.php";
            }

            logUserActivity($dbh, $_SESSION['email'], $_SERVER['REMOTE_ADDR'], 1, $_SESSION['username'], $_SESSION['sid'], $_SESSION['fname'], $_SESSION['lname']);
            header("Location: $redirectPage");
            exit();
        } else {
            logUserActivity($dbh, $_SESSION['email'], $_SERVER['REMOTE_ADDR'], 0, $_SESSION['username'], $_SESSION['sid'], 'Blocked Account', '');
            echo "<script>alert('Your account was blocked please approach Admin');document.location ='LoginPage.php';</script>";
        }
    } else {
        logUserActivity($dbh, $email, $_SERVER['REMOTE_ADDR'], 0, $email, 'N/A', 'Potential Hacker', '');
        echo "<script>alert('Incorrect username or password!');document.location ='LoginPage.php';</script>";
    }
}
?>

<html>
<style>
.error-msg {
    margin: 10px 0;
    display: block;
    background: crimson;
    color: #fff;
    border-radius: 5px;
    font-size: 20px;
    padding: 10px;
}
</style>
<?php @include("includes/head.php"); ?>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="card">
            <div class="card-body login-card-body">
                <div class="login-logo">
                    <h3><center>GVPCEW</center></h3>
                    <center><img src="company/logo.jpg" width="140" height="130" class="user-image" alt="User Image"/></center>
                </div>
                <?php
                if (isset($error)) {
                    foreach ($error as $error) {
                        echo '<span class="error-msg">' . $error . '</span>';
                    }
                }
                ?>
                <form action="" method="post">
                    <div class="input-group mb-3">
                        <input type="text" name="email" class="form-control" placeholder="Email" required value="<?php if(isset($_COOKIE["user_login"])) { echo $_COOKIE["user_login"]; } ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required value="<?php if(isset($_COOKIE["userpassword"])) { echo $_COOKIE["userpassword"]; } ?>">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input type="checkbox" id="remember"  <?php if(isset($_COOKIE["user_login"])) { ?> checked <?php } ?>>
                                <label for="remember">
                                    Remember Me
                                </label>
                            </div>
                        </div>
                        <div><p class="mb-1">
                            <a href="ForgotPassword.php" style="font-size: 18.5px;">Forgot Password?</a>
                        </p></div>
                    </div>
                    <div class="col-15" style="padding-top: 1%;">
                        <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                    </div>
                    <h5 style="padding-left:10%; padding-top:1%">Not a Member? <a href="registerform.php">Sign Up now</a></h5>
                </form>
            </div>
        </div>
    </div>
    <?php @include("includes/foot.php"); ?>
    <script src="assets/js/core/js.cookie.min.js"></script>
</body>
</html>

