<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sid']==0)) {
  header('location:logout.php');
} else {
  if(isset($_POST['submit'])) {
    $eid = $_SESSION['sid'];
    $userimage = $_FILES["userimage"]["name"];
    $allowed_extensions = array("jpg", "jpeg", "png");

    // Get the extension of the file
    $extension = pathinfo($userimage, PATHINFO_EXTENSION);

    // Check if the file is allowed
    if (!in_array($extension, $allowed_extensions)) {
      $error[] = 'Invalid file format. Only JPG, JPEG, and PNG files are allowed.';
    } else {
      move_uploaded_file($_FILES["userimage"]["tmp_name"], "studentimages/".$_FILES["userimage"]["name"]);
      $sql = "UPDATE students SET studentImage = :userimage WHERE studentid = :eid";
      $query = $dbh->prepare($sql);
      $query->bindParam(':userimage', $userimage, PDO::PARAM_STR);
      $query->bindParam(':eid', $eid, PDO::PARAM_STR);

      if ($query->execute()) {
        $success[] = 'Image updated successfully.';
        $redirect = true;
      } else {
        $error[] = 'Something went wrong. Please try again later.';
      }
    }
  }
  $user_role = $_SESSION['role']; 
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

.image-container {
    text-align: center;
    margin-bottom: 20px;
}

.image-container img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 4px solid gray;
    object-fit: cover;
}

.image-name {
    margin-top: 10px;
    font-weight: bold;
    font-size: 18px;
    color: #333;
}

.form-group button {
    margin-left: auto;
    display: block;
}
</style>

<body class="hold-transition sidebar-mini layout-fixed">
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
    <div class="col-lg-9" style="padding-left: 25%;">
      <div class="card mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h4 class="m-1 font-weight-bold text-primary">Update User Image</h4>
        </div>
        <div class="card-body">

          <!-- Display Success or Error Message -->
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
                  window.location.href = 'UpdateImage.php';
                }, 1500); // 1.5 seconds delay
              </script>";
            }
          }
          ?>

          <form method="post" enctype="multipart/form-data">
            <?php
            $eid = $_SESSION['sid'];
            $sql = "SELECT * FROM students WHERE studentid = :eid";                                    
            $query = $dbh->prepare($sql);
            $query->bindParam(':eid', $eid, PDO::PARAM_STR);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ);

            if($query->rowCount() > 0) {
              foreach($results as $row) {    
                ?>
                <br>
                <div class="image-container">
                  <img src="studentimages/<?php echo $row->studentImage;?>" alt="Active Image">
                  <div class="image-name">Active Image</div>
                </div>
                <div class="form-group col-md-6">
                  <label>Upload New Image</label>
                  <input type="file" name="userimage" id="userimage" class="file-upload-default" accept=".jpg, .jpeg, .png" required>
                </div>
                <?php 
              }
            } ?>
            <br>
             <div class="modal-footer text-right">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    <!-- Go Back Button -->
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Go Back</button>
                                </div>
          </form>
        </div>
      </div>
    </div>
    <!-- /.content-header -->
  </div>
  <!-- /.content-wrapper -->
  <?php @include("includes/foot.php"); ?>
  <?php @include("includes/footer.php"); ?>
</body>
</html>

<?php 
} 
?>
