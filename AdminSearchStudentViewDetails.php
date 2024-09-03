<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']) == 0) {
  header('location:logout.php');
} 
$user_role = $_SESSION['role']; 
if ($user_role == 'Student') {
  @include("includes/header.php");
  @include("includes/student_sidebar.php");
} else if ($user_role == 'Admin') {
  @include("includes/header.php");
  @include("includes/sidebar.php");
}


?>
  <?php @include("includes/head.php"); ?>
  <style>

.profile-image img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    border: 3px solid grey;
    position: relative;
    top: 5%;
    left: 50px; /* Positioning to the left and partially overlapping the background */
    transform: translateY(-50%);
}
.user-info {
    
    padding-left: 3%;

    font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
}
</style>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
 
     <div class="content-wrapper">
      <br>
      <div class="col-lg-15" style="padding-left: 13%;padding-right: 13%;">
        <div class="card mb-15">
        
          <div class="card-body">
            <?php
          if (isset($_GET['user'])) {
    $user = $con->real_escape_string($_GET['user']);
    $sql = "SELECT * FROM students WHERE studentid LIKE '%$user%'";
    $result = $con->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
         <div class="card mb" >
                        <div  >
<img  src="studentimages\background.jpg" width=100% height="200px" alt="User Image"/>
                        
<div class="profile-image">
  <img  src="studentimages/<?php echo htmlentities($row['studentImage']);?>" width="100px" height="`100px" id="profileImage"> </div>
  <div class="user-info">
    <h4><?= $row['username']; ?></h4> 
    <h5><?= $row['studentid']; ?></h5>
    <h5><?=  mb_strtoupper($row['spec']); ?></h5>
    <h5><?= $row['clgname']; ?></h5>
    <h5><?= $row['email']; ?></h5>
    <br></div>
  </div>

                        </div>
                         </div>   


    <?php
  } else {
        echo "User not found.";
    }
} else {
    echo "No user ID provided.";
}
?>

<div class="card-body">
  <h3 style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;color:blueviolet"><center><b>Projects</b></center></h3>
  <hr style="background-color:darkgreen;">
    <?php
           if (isset($_GET['user']))
            {
    $user = $con->real_escape_string($_GET['user']);
    $ret2=mysqli_query($con,"select * from  projects where studentid='$user'");
    ?>
    <h5><b>Total Projects:</b>&nbsp;<?php echo htmlentities($ret2->num_rows);?></h5> 
    <?php
    while ($row=mysqli_fetch_array($ret2)){
      ?>
      

    <div class="card mb">
      <br>
    <h3 style="padding-left: 1%;"><b><?php echo htmlentities($cnt);?>)</b>&nbsp;<u style="font-family:Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;"><?php echo htmlentities($row['title']);?></u></h3>
                        <div style="padding-left: 4%;"> 
                          <h5><b>Description:</b>&nbsp;<?php echo htmlentities($row['description']);?></h5>
                          <h5><b>Domain:</b>&nbsp;<?php echo htmlentities($row['domain']);?></h5>
                          <h5><b >Mentor:</b>&nbsp;<?php echo htmlentities($row['mentor']);?></h5>
                          
                          <h5><b>ProjectType:</b>&nbsp;<?php echo htmlentities($row['type']);?></h5>
                        <?php  if ($row['type'] == 'Group') {
            echo "<h5><b>Team Members:</b></h5> ";
            $members = explode(",", $row['members']);
            echo "<ul>";
            foreach ($members as $member) {
                echo "<li>" . htmlspecialchars(trim($member)) . "</li>";
            }
            echo "</ul>";
           
          }?>
          <div style="padding-right: 5%;">
                          <iframe src="projectimages/<?php echo htmlentities($row['doc']);?>" width="100%" height="350"></iframe></div>
                          
                         <p></p>
                        </div>
    </div>
    <br>
                        <?php $cnt=$cnt+1;
    }
                      } ?>                             
        </div>
    </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
            </div>
     </div>   
            
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
  ?>
