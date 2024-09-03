<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if(isset($_POST['login'])) {
    $password1 = $_POST['password']; 
    $password2 = $_POST['password1']; 

    if($password1 != $password2) {
        $error[] = 'Password and Confirm Password fields do not match!';
    } else {
        $email = $_POST['email'];
        $number = $_POST['number'];
        $newpassword = md5($_POST['password']);

        $sql = "SELECT email FROM students WHERE email=:email and number=:number";
        $query = $dbh->prepare($sql);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':number', $number, PDO::PARAM_STR);
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        if($query->rowCount() > 0) {
            $con = "UPDATE students SET password=:newpassword WHERE email=:email and number=:number";
            $chngpwd1 = $dbh->prepare($con);
            $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
            $chngpwd1->bindParam(':number', $number, PDO::PARAM_STR);
            $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
            $chngpwd1->execute();
            $success[] = 'Your password was successfully changed';
            $redirect = true;
        } else {
            $error[] = 'Email ID or Mobile number is invalid';
        }
    }
}
?>

<?php @include("includes/head.php"); ?>
<style>
.error-msg {
    margin: 10px 0;
    display: block;
    background: crimson;
    color: #fff;
    border-radius: 5px;
    font-size: 18px;
    padding: 10px;
}

.success-msg {
    margin: 10px 0;
    display: block;
    background: green;
    color: #fff;
    border-radius: 5px;
    font-size: 18px;
    padding: 10px;
}
</style>
<body class="hold-transition login-page">
  <!-- Logo box -->
  <div class="login-box">  
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <div class="login-logo">
          <center>
            <img src="company/logo.jpg" width="150" height="130" class="user-image" alt="User Image"/>
          </center>
        </div>

        <!-- Display error or success messages -->
        <?php
        if (isset($error)) {
            foreach ($error as $errorMsg) {
                echo '<span class="error-msg">' . $errorMsg . '</span>';
            }
        }

        if (isset($success)) {
            foreach ($success as $successMsg) {
                echo '<span class="success-msg">' . $successMsg . '</span>';
            }
            // Redirect to the login page after a delay
            if (isset($redirect)) {
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'LoginPage.php';
                    }, 1500); // 1.5 seconds delay
                </script>";
            }
        }
        ?>

        <p class="login-box-msg"><strong style="color: blue">Don't worry, we've got your back</strong></p>
        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="text" name="email" class="form-control" placeholder="Email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="number" class="form-control" placeholder="Mobile" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-phone"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="New Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="password1" class="form-control" placeholder="Confirm Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <button type="submit" name="login" class="btn btn-primary btn-block">Reset</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <?php @include("includes/foot.php"); ?>
</body>
</html>
