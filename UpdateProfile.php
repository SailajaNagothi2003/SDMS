<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');


if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_SESSION['sid'];
        $email = $_POST['email'];
        $number = $_POST['number'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $username = $_POST['username'];

        // Gmail validation
       

        // Check if username, email, or number already exists for another user
    $sql = "SELECT * FROM students WHERE (username=:username OR email=:email OR number=:number) AND studentid != :eid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':username', $username, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':number', $number, PDO::PARAM_STR);
    $query->bindParam(':eid', $eid, PDO::PARAM_STR);
    $query->execute();
    if (!preg_match("/^[a-zA-Z][a-zA-Z0-9._]*@gmail\.com$/", $email)) {
      $error[] = "Invalid Gmail format. It must start with a letter and end with '@gmail.com'.";
  }
    else if($query->rowCount() > 0) {
      $error[] = "Username, Email, or Contact Number already exists. Please choose different values.";
    }
        else {
            $sql = "UPDATE students SET fname=:fname, username=:username, number=:number, email=:email, lname=:lname WHERE studentid=:eid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':fname', $fname, PDO::PARAM_STR);
            $query->bindParam(':lname', $lname, PDO::PARAM_STR);
            $query->bindParam(':username', $username, PDO::PARAM_STR);
            $query->bindParam(':email', $email, PDO::PARAM_STR);
            $query->bindParam(':number', $number, PDO::PARAM_STR);
            $query->bindParam(':eid', $eid, PDO::PARAM_STR);
            $query->execute();

            $success[] = "Profile updated successfully!";
            $redirect = true; // Set redirection flag
        }
    }

    $user_role = $_SESSION['role']; 
    ?>

    <?php @include("includes/head.php"); ?>

    <body class="hold-transition sidebar-mini layout-fixed">
      <style>
        .error-msg {
    margin: 10px 0;
    display: block;
    background: crimson;
    color: #fff;
    border-radius: 5px;
    font-size: 18px;
    padding: 10px;
    text-align: center;
}

.success-msg {
    margin: 10px 0;
    display: block;
    background: green;
    color: #fff;
    border-radius: 5px;
    font-size: 18px;
    padding: 10px;
    text-align: center;
}
      </style>
        <div class="wrapper">
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
                <div class="col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h4 class="m-0 font-weight-bold text-primary" class="text-right">Update User Profile</h4>
                        </div>
                        <div class="card-body">

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
            if (isset($redirect)) {
              echo "<script>
                setTimeout(function() {
                  window.location.href = 'UpdateProfile.php';
                }, 1500); // 1.5 seconds delay
              </script>";
            }
          }
          ?>

                            <form method="post">
                                <?php
                                $eid = $_SESSION['sid'];
                                $sql = "SELECT * FROM students WHERE studentid=:eid";                                    
                                $query = $dbh->prepare($sql);
                                $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                                $query->execute();
                                $results = $query->fetchAll(PDO::FETCH_OBJ);

                                if ($query->rowCount() > 0) {
                                    foreach ($results as $row) {    
                                        ?>
                                        <div class="container rounded bg-white mt-5">
                                            <div class="row">
                                                <div class="col-md-4 border-right">
                                                    <div class="d-flex flex-column align-items-center text-center p-3 py-5" >
                                                        <?php 
                                                        if ($row->userimage == "avatar15.jpg") { ?>
                                                            <img class="rounded-circle mt-5" src="studentimages/avatar15.jpg" width="150" height="150"style="border: 2px solid gray;">
                                                        <?php } else { ?>
                                                            <img class="rounded-circle mt-5" src="studentimages/<?php echo $row->studentImage; ?>" width="150" height="150" style="border: 2px solid gray;">
                                                        <?php } ?>
                                                        <div style="padding-top: 5%;">
                                                            <span class="font-weight-bold" style="font-family:cursive; font-size:20px;color:darkblue"><?php echo strtoupper($row->fname) . " " . strtoupper($row->lname); ?></span>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="UpdateImage.php?id=<?php echo $id; ?>"><h4>Edit Image</h4></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="p-3 py-5">
                                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                                            
                                                            <h6 class="text-right">Edit Profile</h6>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-md-6"><label>First Name</label><input type="text" class="form-control" name="fname" value="<?php echo $row->fname; ?>" required></div>
                                                            <div class="col-md-6"><label>Last Name</label><input type="text" class="form-control" value="<?php echo $row->lname; ?>" name="lname" required></div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6"><label>Email</label><input type="text" class="form-control" name="email" value="<?php echo $row->email; ?>" required></div>
                                                            <div class="col-md-6"><label>Contact Number</label><input type="text" class="form-control" value="<?php echo $row->number; ?>" pattern="[6789][0-9]{9}" name="number" required></div>
                                                        </div>
                                                        <div class="row mt-3">
                                                            <div class="col-md-6">
                                                                <label class="form-group">User Name</label>
                                                                <input type="text" class="form-control" name="username" value="<?php echo $row->username; ?>" required>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-group">Role</label>
                                                                <input type="text" class="form-control" name="user" value="<?php echo $row->user; ?>" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="modal-footer text-right">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    <!-- Go Back Button -->
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Go Back</button>
                                </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php 
                                    }
                                } ?>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- /.content-header -->
            </div>
            <!-- /.content-wrapper -->
            <?php @include("includes/foot.php"); ?>
            <?php @include("includes/footer.php"); ?>
        </div>
    </body>
    </html>
<?php } ?>
