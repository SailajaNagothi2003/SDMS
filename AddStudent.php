<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']) == 0) {
  header('location:logout.php');
} 
$errors = [];
$formData = [
    'fname' => '',
    'mname' => '',
    'lname' => '',
    'number' => '',
    'studentid' => '',
    'username' => '',
    'email' => '',
    'password' => '',
    'cpassword' => '',
    'gender' => '',
    'clgname' => '',
    'spec' => '',
    'start_year' => '',
    'end_year' => '',
    'marks' => '',
    'photo' => ''
];

  if(isset($_POST['submit']))
  {
    foreach ($formData as $key => $value) {
      $formData[$key] = $_POST[$key];
  }
  
  $fname = $formData['fname'];
  $mname = $formData['mname'];
  $lname = $formData['lname'];
  $number = $formData['number'];
  $studentid = $formData['studentid'];
  $username = $formData['username'];
  $email = $formData['email'];
  $password = md5($formData['password']);
  $cpassword = md5($formData['cpassword']);
  $gender = $formData['gender'];
  $clgname = $formData['clgname'];
  $spec = $formData['spec'];
  $start_year = $formData['start_year'];
  $end_year = $formData['end_year'];
  $marks = $formData['marks'];
  $photo = $_FILES['photo']['name'];

    move_uploaded_file($_FILES["photo"]["tmp_name"],"studentimages/".$_FILES["photo"]["name"]);

    $fileNameCmps = explode(".", $photo);
    $fileExtension = strtolower(end($fileNameCmps));
    $allowedfileExtensions = array('jpeg', 'jpg', 'png');

   
    $select1 = " SELECT * FROM students WHERE email = '$email' ";
    $select2 = " SELECT * FROM students WHERE studentid = '$studentid' ";
    $select3 = " SELECT * FROM students WHERE number = '$number' ";
    $select4 = " SELECT * FROM students WHERE username = '$username' ";
   $result1 = mysqli_query($con, $select1);
   $result2 = mysqli_query($con, $select2);
   $result3 = mysqli_query($con, $select3);
   $result4 = mysqli_query($con, $select4);
   $select5 = " SELECT * FROM students WHERE studentImage= '$photo' ";
    $result5 = mysqli_query($con, $select5);

    function validateEmailUsingFilter($email) {
      return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
  }

  function validateEmailUsingRegex($email) {
      $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
      return preg_match($pattern, $email) === 1;
  }

  $isValidFilter = validateEmailUsingFilter($email);
  $isValidRegex = validateEmailUsingRegex($email);

  if (!$isValidFilter || !$isValidRegex) {
 
    $error[]= "Invalid email address.";
  }

  else if(mysqli_num_rows($result1) > 0){

   // echo "<script>alert( 'Email already exist!');</script>";
   $error[] = 'Email already exist! ';
   }
   else if(mysqli_num_rows($result4) > 0){
    //echo "<script>alert( 'already exist!');</script>";
    $error[] = 'UserName  already exist! ';
  }
   else if(mysqli_num_rows($result3) > 0){
    //echo "<script>alert( 'Phone Number already exist!');</script>";
    $error[] = 'Phone Number already exist! ';
  }
  else if(mysqli_num_rows($result2) > 0){
    //echo "<script>alert( 'already exist!');</script>";
    $error[] = 'Student ID  already exist! ';
  }
 
    // Check if the file extension is valid
    else if ($fileExtension != "jpeg" && $fileExtension != "jpg" && $fileExtension != "png") {

      $error[] ='only jpg ,jpeg, png files are allowed!';
      //echo "Upload failed. Only PDF files are allowed.";
  }
  else if  (mysqli_num_rows($result5) > 0) {
    $error[] = 'Photo already exists!';
    //echo " A file with this name already exists.";
} 
 
else{
  if($password != $cpassword){
    $error[] = 'Password and confirm password not matched!';
   // echo "<script>alert( 'Password and confirm password not matched!');</script>";
    }
 else{
    $query=mysqli_query($con, "insert into  students(fname,mname,lname,number,studentid,email,password,gender,clgname,spec,start_year,end_year,marks,username,studentImage) value(' $fname','$mname','$lname','$number','$studentid','$email','$password','$gender',' $clgname','$spec','$start_year','$end_year','$marks','$username','$photo')");
    if ($query) {
      $_SESSION['studentid'] = $row['studentid'];
      $success[] = 'Student has been registered successfully.';
      $redirect = true;
      ;
    }
    else
    {
      echo "<script>alert('Something Went Wrong. Please try again.');</script>";    
    }
  }
  }
}
  ?>

<!DOCTYPE html>
  <html>
    <style>
      .error-msg{
   margin:10px 0;
   display: block;
   background: crimson;
   color:#fff;
   border-radius: 5px;
   font-size: 20px;
   padding:10px;
}
.success-msg {
    margin: 10px 0;
    display: block;
    background: green;
    color: #fff;
    border-radius: 5px;
    font-size: 20px;
    padding: 10px;
    text-align: center;
}
.required-field:after {
      content: '*';
      color: red;
      margin-left: 5px;
    }
</style>
  <?php @include("includes/head.php"); ?>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <!-- Navbar -->
      <?php @include("includes/header.php"); ?>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <?php @include("includes/sidebar.php"); ?>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="MainPage.php"><i class="fas fa-home"></i> Home</a></li>
                </ol>
              </div>
            </div>
          </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
          <div class="container-fluid">
              <div class="col-md-20">
                <!-- general form elements -->
                <div class="card card-primary">
                  <div class="card-header">
                  <h2 class="card-title">  <center><b>Student Details</b></center></h2>
                  </div>
                  <!-- /.card-header -->
                  <!-- form start -->
                  <form role="form" method="post" id="validationForm" enctype="multipart/form-data">
                    <div class="card-body">
                      <span style="color: brown"><h5>Personal details</h5></span>
                      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg"><center>'.$error.'</center></span>';
         };
      };

      if (isset($success)) {
        foreach ($success as $msg) {
            echo '<span class="success-msg">' . $msg . '</span>';
        }
        // Redirect to the login page after 1.5 seconds
        if (isset($redirect)) {
            echo "<script>
                setTimeout(function() {
                    window.location.href = 'AddStudent.php';
                }, 1500); // 1.5 seconds
            </script>";
        }
    }
      ?>
                      <hr>
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="fname" class="required-field">First Name</label>
                          <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" value="<?php echo htmlspecialchars($formData['fname']); ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="mname">Middle Name</label>
                          <input type="text" class="form-control" id="mname" name="mname" placeholder="Middle Name" value="<?php echo htmlspecialchars($formData['mname']); ?>">
                        </div>
                        <div class="form-group col-md-4">
                          <label for="lname" class="required-field">Last Name</label>
                          <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" value="<?php echo htmlspecialchars($formData['lname']); ?>" required>
                        </div>
          
                        <div class="form-group col-md-4">
                          <label for="username" class="required-field">UserName</label>
                          <input type="text" class="form-control" id="username" name="username" placeholder="username"value="<?php echo htmlspecialchars($formData['username']); ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="number" class="required-field">Contact Number</label>
                          <input type="text" class="form-control" id="number" name="number" placeholder="Contact Number" required pattern="[6789][0-9]{9}" title="Phone number must be exactly 10 digits, starting with 6, 7, 8, or 9." value="<?php echo htmlspecialchars($formData['number']); ?>" required>
                          <span id="phone-error-message" class="error-message"></span>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="email" class="required-field">Email</label>
                          <input type="text" class="form-control" id="email" name="email" placeholder="Email" pattern="[a-zA-Z0-9._%+-]+@gmail\.com" 
                          title="Email must be a valid Gmail address (e.g., example@gmail.com)"  value="<?php echo htmlspecialchars($formData['email']); ?>" required>
                          <span id="email-error-message" class="error-message"></span>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="password" class="required-field">Password</label>
                          <input type="password" class="form-control" id="password" name="password" placeholder="Password"  value="<?php echo htmlspecialchars($formData['password']); ?>" required >
                        </div>
                        <div class="form-group col-md-4">
                          <label for="cpassword" class="required-field">Confirm Password</label>
                          <input type="password" class="form-control" id="cpassword" name="cpassword" placeholder="Password"  value="<?php echo htmlspecialchars($formData['cpassword']); ?>" required >
                        </div>
                        <div class="form-group col-md-4">
                          <label for="gender" class="required-field">Gender</label>
                          <select type="select" class="form-control" id="gender" name="gender"  required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?php if ($formData['gender'] == 'Male') echo 'selected'; ?>>Male</option>
                            <option value="Female" <?php if ($formData['gender'] == 'Female') echo 'selected'; ?>>Female</option>
                          </select>
                        </div>
                      
                      </div>
                      <hr>
                      <span style="color: brown"><h5>College details</h5></span>
                      <hr>
                      <div class="row">
                        <div class="form-group col-md-4">
                          <label for="studentid" class="required-field">Student ID</label>
                          <input type="text" class="form-control" id="studentid" name="studentid" placeholder="Enter student ID correctly" value="<?php echo htmlspecialchars($formData['studentid']); ?>" required>
                        </div>
                        <div class="form-group col-md-4">
                          <label for="clgname" class="required-field" >College Name</label>
                          <input type="text" class="form-control" id="clgname" name="clgname" placeholder="College" value="<?php echo htmlspecialchars($formData['clgname']); ?>" required >
                        </div>
                        <div class="form-group col-md-4">
                          <label for="spec" class="required-field">Specialisation</label>
                          <select type="select" class="form-control" id="spec" name="spec" required>
                            <option value="">Select Branch</option>
                            <option value="cse"  <?php if ($formData['spec'] == 'cse') echo 'selected'; ?>>CSE</option>
                            <option value="csm"  <?php if ($formData['spec'] == 'csm') echo 'selected'; ?> >CSM</option>
                            <option value="ece" <?php if ($formData['spec'] == 'ece') echo 'selected'; ?>>ECE</option>
                            <option value="eee" <?php if ($formData['spec'] == 'eee') echo 'selected'; ?>>EEE</option>
                            <option value="infotech" <?php if ($formData['spec'] == 'infotech') echo 'selected'; ?>>IT</option>
                          </select>
                        </div>

                        <div class="form-group col-md-4">
    <label for="start_year" class="required-field">Year of Admission: </label>
    <select class="form-control" id="start_year" name="start_year" onchange="updateEndYearOptions()" required>
        <option value="">Starting year</option>
        <?php
        $startYear = 2000;
        $endYear = 2050;
        for ($year = $startYear; $year <= $endYear; $year++) {
            $selected = ($formData['start_year'] == $year) ? 'selected' : '';
            echo "<option value=\"$year\" $selected>$year</option>";
        }
        ?>
    </select>
</div>

<div class="form-group col-md-4">
    <label for="end_year" class="required-field">Year of Graduation</label>
    <select class="form-control" id="end_year" name="end_year" required>
        <option value="">Ending year</option>
        <script>
        function updateEndYearOptions() {
            var startYear = parseInt(document.getElementById('start_year').value);
            var endYearSelect = document.getElementById('end_year');
            var currentEndYear = parseInt(endYearSelect.value);
            endYearSelect.innerHTML = '<option value="">Ending year</option>';
            
            if (isNaN(startYear)) {
                return;
            }
            var minEndYear = startYear + 4;
            var maxEndYear = 2050; 
            for (var year = minEndYear; year <= maxEndYear; year++) {
                var option = document.createElement('option');
                option.value = year;
                option.text = year;
                if (year === currentEndYear) {
                    option.selected = true;
                }
                
                endYearSelect.appendChild(option);
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            updateEndYearOptions();
        });
        </script>
    </select>
</div>
                        <div class="form-group col-md-4">
                          <label for="marks" class="required-field">Academic Percentage</label>
                          <input type="number" class="form-control" id="marks" name="marks" placeholder="Percentage" value="<?php echo htmlspecialchars($formData['marks']); ?>" required>
                        </div>
                      
                        <div class="form-group col-md-4">
                <label for="photo" class="required-field">Student Photo (jpg, jpeg, png only)</label>
                <input type="file" class="" name="photo" id="photo" accept="image/jpeg, image/png, image/jpg" required>
            </div>

                      </div>
                    <!-- /.card-body -->
                   <center> <div class="card-footer">
                      <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div></center>
                   <!--?php
                   if(isset($success)){
                    
                      echo '<span class="success-alert"><center>'.$success.'</center></span>';
                      
                    };
                 ?-->
                  </form>
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.container-fluid -->
        </section>
        <!-- /.content -->
      </div>
      <!-- /.content-wrapper -->
      <div style="padding-left: 22%;"><?php @include("includes/footer.php"); ?></div>

    </div>

    <!-- ./wrapper -->
    <?php @include("includes/foot.php"); ?>
  </body>
  <script>
function updateEndYearOptions(selectedEndYear) {
    var startYear = parseInt(document.getElementById('start_year').value);
    var endYearSelect = document.getElementById('end_year');
    endYearSelect.innerHTML = '<option value="">Ending year</option>';

    if (isNaN(startYear)) {
        return;
    }
    var minEndYear = startYear + 4;
    var maxEndYear = 2050;
    for (var year = minEndYear; year <= maxEndYear; year++) {
        var option = document.createElement('option');
        option.value = year;
        option.text = year;
        if (year == selectedEndYear) {
            option.selected = true;
        }
        endYearSelect.appendChild(option);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    var selectedEndYear = <?php echo json_encode($formData['end_year']); ?>;
    updateEndYearOptions(selectedEndYear);
});
</script>
  </html>