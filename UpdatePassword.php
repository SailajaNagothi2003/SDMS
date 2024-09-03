<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
    exit();
} else {
    if (isset($_POST['submit'])) {
        $pid = $_SESSION['sid'];
        $cpassword = md5($_POST['password']);
        $newpassword = md5($_POST['password1']);
        $confirmpassword = md5($_POST['password2']);

        // Check if the new password and confirm password are the same
        if ($newpassword != $confirmpassword) {
            $error[] = 'New Password and Confirm Password do not match!';
        } else {
            $sql = "SELECT studentid, password FROM students WHERE studentid=:pid AND password=:cpassword";
            $query = $dbh->prepare($sql);
            $query->bindParam(':pid', $pid, PDO::PARAM_STR);
            $query->bindParam(':cpassword', $cpassword, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetch(PDO::FETCH_OBJ);

            if ($query->rowCount() > 0) {
                // Check if the new password is the same as the current password
                if ($results->password == $newpassword) {
                    $error[] = 'New Password cannot be the same as the current password!';
                } else {
                    // Update the password
                    $con = "UPDATE students SET password=:newpassword WHERE studentid=:pid";
                    $chngpwd1 = $dbh->prepare($con);
                    $chngpwd1->bindParam(':pid', $pid, PDO::PARAM_STR);
                    $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
                    $chngpwd1->execute();

                    $success[] = 'Your password was successfully changed';
                    $redirect = true;
                }
            } else {
                $error[] = 'Your current password is wrong';
            }
        }
    }

    // Assuming the session contains user role information
    $user_role = $_SESSION['role']; // This should be set during login, e.g., 'student' or 'admin'
?>
<!DOCTYPE html>
<html>
<?php @include("includes/head.php"); ?>
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

.success-msg {
    margin: 10px 0;
    display: block;
    background: green;
    color: #fff;
    border-radius: 5px;
    font-size: 20px;
    padding: 10px;
}
</style>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar and Sidebar based on user role -->
        <?php 
        if ($user_role == 'Student') {
            @include("includes/header.php");
            @include("includes/student_sidebar.php");
        } else if ($user_role == 'Admin') {
            @include("includes/header.php");
            @include("includes/sidebar.php");
        }
        ?>
        
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <br>
            <div class="card" style="padding-left: 15%; padding-top: 2%">
                <div class="col-md-10">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Change Password</h3>
                        </div>
                        <div class="card-body">
                        <?php
                        if (isset($error)) {
                            foreach ($error as $err) {
                                echo '<span class="error-msg">' . $err . '</span>';
                            }
                        }
                        if (isset($success)) {
                            foreach ($success as $msg) {
                                echo '<span class="success-msg">' . $msg . '</span>';
                            }
                            // Redirect to the login page after 1.5 seconds
                            if (isset($redirect)) {
                                echo "<script>
                                    setTimeout(function() {
                                        window.location.href = 'LoginPage.php';
                                    }, 1500); // 1.5 seconds
                                </script>";
                            }
                        }
                        ?>
                            <form role="form" method="post" enctype="multipart/form-data" class="form-horizontal">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Old Password</label>
                                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">New Password</label>
                                        <input type="password" name="password1" class="form-control" id="exampleInputPassword1" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPassword1">Confirm Password</label>
                                        <input type="password" name="password2" class="form-control" id="exampleInputPassword1" required>
                                    </div>
                                </div>
                                <div class="modal-footer text-right">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    <!-- Go Back Button -->
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Go Back</button>
                                </div>
                            </form> 
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->
        </div>
        <!-- /.content-wrapper -->
        <?php @include("includes/footer.php"); ?>
        <?php @include("includes/foot.php"); ?>
    </div>
</body>
</html>
<?php
}
?>
