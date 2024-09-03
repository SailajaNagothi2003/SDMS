<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']) == 0) {
  header('location:logout.php');
} 
if(isset($_POST['submit']))
{
  $sid=$_SESSION['edit_id'];
  $fname=$_POST['fname'];
  $lname=$_POST['lname'];
  $number=$_POST['number'];
  $email=$_POST['email'];
  $user=$_POST['user'];
  $gender=$_POST['gender'];
  $username=$_POST['username'];
  $sql="update students set fname=:fname,username=:username,lname=:lname,number=:number,gender=:gender,user=:user,email=:email  where studentid=:sid";
  $query = $dbh->prepare($sql);
  $query->bindParam(':fname',$fname,PDO::PARAM_STR);
  $query->bindParam(':lname',$lname,PDO::PARAM_STR);
  $query->bindParam(':username',$username,PDO::PARAM_STR);
  $query->bindParam(':number',$number,PDO::PARAM_STR);
  $query->bindParam(':email',$email,PDO::PARAM_STR);
  $query->bindParam(':gender',$gender,PDO::PARAM_STR);
  $query->bindParam(':user',$user,PDO::PARAM_STR);
  $query->bindParam(':sid',$sid,PDO::PARAM_STR);
  $query->execute();
  if ($query->execute()) {
    echo "<script>alert('updated successfuly.');</script>";
    echo "<script>window.location.href ='student_list.php'</script>";
  }else{
    echo "<script>alert('something went wrong, please try again later');</script>";
  }
}

if(isset($_POST['save']))
{
  $sid=$_SESSION['edit_id']; 
  $studentid=$_POST['studentid'];
  $clgname=$_POST['clgname'];
  $spec=$_POST['spec'];
  $start_year=$_POST['start_year'];
  $end_year=$_POST['end_year'];
  $marks=$_POST['marks'];
  $sql="update students set studentid=:studentid,clgname=:clgname,spec=:spec,start_year=:start_year,end_year=:end_year,marks=:marks where studentid='$sid'";
  $query = $dbh->prepare($sql);
  $query->bindParam(':studentid',$studentid,PDO::PARAM_STR);
  $query->bindParam(':clgname',$clgname,PDO::PARAM_STR);
  $query->bindParam(':spec',$spec,PDO::PARAM_STR);
  $query->bindParam(':start_year',$start_year,PDO::PARAM_STR);
  $query->bindParam(':end_year',$end_year,PDO::PARAM_STR);
  $query->bindParam(':marks',$marks,PDO::PARAM_STR);
  $query->execute();
  if ($query->execute()) {
    echo "<script>alert('updated successfuly`.');</script>";
    echo "<script>window.location.href ='student_list.php'</script>";
  }else{
    echo "<script>alert('something went wrong, please try again later');</script>";
  }
}

if(isset($_POST['save2']))
{
  $sid=$_SESSION['edit_id'];
  $studentimage=$_FILES["studentimage"]["name"];
  move_uploaded_file($_FILES["studentimage"]["tmp_name"],"studentimages/".$_FILES["studentimage"]["name"]);
  $sql="update students set studentImage=:studentimage where studentid='$sid' ";
  $query = $dbh->prepare($sql);
  $query->bindParam(':studentimage',$studentimage,PDO::PARAM_STR);
  $query->execute();
  if ($query->execute()) {
    echo "<script>alert('updated successfuly.');</script>";
    echo "<script>window.location.href ='student_list.php'</script>";
  }else{
    echo "<script>alert('something went wrong, please try again later');</script>";
  }
}

?>

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
</style>
<!-- Content Wrapper. Contains page content -->
<div class="card-body">
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
    <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <div class="row">
       <?php
        $eid=$_POST['edit_id'];
        $ret=mysqli_query($con,"select * from  students where studentid='$eid'");
        $cnt=1;
        while ($row=mysqli_fetch_array($ret))
        {
 
          $_SESSION['edit_id']=$row['studentid']; 
          ?>
         <div class="col-md-3" style="padding-top: 12%">
           <!-- Profile Image -->
           <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center" >
                <img style="border:2px solid gray" class="img-circle" src="studentimages/<?php echo htmlentities($row['studentImage']);?>" width="150" height="150" class="user-image"
                alt="User profile picture">
              </div>

              <h3 class="profile-username text-center"><?php  echo $row['username'];?></h3>
              <p class="text-muted text-center"><strong></strong></p>

              <ul class="list-group list-group-unbordered mb-3">
                <li class="list-group-item">
                <center> <b><?php  echo $row['studentid'];?></b></center>
                </li>
              </ul>

            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <center><h1>STUDENT DETAILS</h1></center>
          <div class="card">
            <div class="card-header p-2">
              <ul class="nav nav-pills">
                <li class="nav-item"><a class="nav-link active" href="#companydetail" data-toggle="tab">Personal Details</a></li>
                <li class="nav-item"><a class="nav-link" href="#companyaddress" data-toggle="tab">College Details</a></li>
                <li class="nav-item"><a class="nav-link" href="#change" data-toggle="tab">Update Image</a></li>
              </ul>
            </div><!-- /.card-header -->
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="companydetail">
                  <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                    <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                         <label for="companyname">First Name</label>
                         <input type="text" class="form-control" name="fname" id="fname" value="<?php  echo $row['fname'];?>"  required>
                       </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                         <label for="companyname">Last Name</label>
                         <input type="text" class="form-control" name="lname" id="lname" value="<?php  echo $row['lname'];?>"  required>
                       </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>UserName</label>
                        <input type="text"  class="form-control" name="username" id="username" value="<?php  echo $row['username'];?>" required>
                      </div>  
                     </div>
                   
                     <div class="col-md-6">
                      <div class="form-group">
                        <label>Contact Number</label>
                        <input class="form-control" name="number" id="number" value="<?php  echo $row['number'];?>" required>
                      </div>  
                     </div>
                    </div>

                    <div class="row">

                     <div class="col-md-6">
                      <div class="form-group">
                        <label>Email</label>
                        <input type="email"  class="form-control" name="email" id="email" value="<?php  echo $row['email'];?>" required>
                      </div>  
                     </div>

                     <div class="col-md-6">
                      <div class="form-group">
                        <label>Gender</label>
                        <input type="gender"  class="form-control" name="gender" id="gender" value="<?php  echo $row['gender'];?>" required>
                      </div>  
                     </div>
                     

                     <div class="col-md-6">
                      <div class="form-group">
                        <label>User</label>
                        <input type="user"  class="form-control" name="user" id="user" value="<?php  echo $row['user'];?>" readonly=true>
                      </div>  
                     </div>
                    
                    </div>
                   
                  <!-- /.card-body -->
                  <div class="modal-footer text-right">
                    <button type="submit" name="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->

              <div class=" tab-pane" id="companyaddress">
                <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >

                  <div class="row">
                    <div class="form-group col-md-6">
                     <label >StudentID</label>
                     <input name="studentid" class="form-control" id="studentid"  value="<?php  echo $row['studentid'];?>" >
                   </div>
                   <div class="form-group col-md-6">
                     <label >Specialisation</label>
                     <input name="spec" class="form-control" id="spec"  value="<?php  echo $row['spec'];?>" >
                   </div>
                  </div>
                  <div class="row">
                   <!-- /.form-group -->
                   <div class="form-group col-md-6">
                     <label >College Name</label>
                     <input name="clgname" class="form-control" id="clgname"  value="<?php  echo $row['clgname'];?>" >
                   </div>
                   <!-- /.form-group -->

                   <div class="col-md-6">
                   <div class="form-group">
                      <label>Academic Percentage</label>
                      <input type="number" class="form-control" name="marks" value="<?php  echo $row['marks'];?>"  required>
                    </div>      
                  </div>
                  <!-- /.col -->
                </div><!-- ./row -->
                <div class="row">
                   <!-- /.form-group -->
                   <div class="form-group col-md-6">
                     <label >Year of Entry</label>
                     <input type="number"  name="start_year" class="form-control" id="start_year"  value="<?php  echo $row['start_year'];?>" >
                   </div>
                   <!-- /.form-group -->

                   <div class="col-md-6">
                   <div class="form-group">
                      <label>Year of Graduation</label>
                      <input type="number" class="form-control" name="end_year" value="<?php  echo $row['end_year'];?>"  required>
                    </div>      
                  </div>
                </div>
                  <!-- /.col -->

                <!-- /.card-body -->
                <div class="modal-footer text-right">
                  <button type="submit" name="save" class="btn btn-primary">Update</button>
                </div>

              </form>
            </div>
             
            <!-- /.tab-pane -->




              <div class=" tab-pane" id="change">
             <div class="row" style="padding-left:30%;">
              <form role="form" id=""  method="post" enctype="multipart/form-data" class="form-horizontal" >
                <br>
              <div class="text-center"  >
                <img style="border:2px solid gray"  src="studentimages/<?php echo htmlentities($row['studentImage']);?>" width="150" height="150" class="user-image"
                alt="User profile picture">
                <h4>Active Image</h4>
              </div>
                <div class="form-group">
                  <div style="padding-top: 5%;">
                  <input type="file" class="" name="studentimage" value="" required></div>
                </div>  

                <div class="modal-footer text-right" style="padding-top: 12%;">
                  <button type="submit" name="save2" class="btn btn-primary">Update</button>
                </div>

              </form>
            </div>
          </div>
 

       
          <!-- /.tab-pane -->
          <?php 
        } 
        ?>
      </div>
      <!-- /.tab-content -->
    </div><!-- /.card-body -->
  </section>
  <!-- /.content -->
</div>
  <!-- /.content-wrapper -->
  </html>