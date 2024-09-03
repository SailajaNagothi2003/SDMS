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
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
     <div class="content-wrapper">
      <br>
      <div class="col-lg-15" style="padding-left: 30%;padding-right: 30%;">
        <div class="card mb-15">
        
          <div class="card-body" style="background-color:lightblue;">
          <?php
            if (isset($_GET['query'])) {
                $searchQuery = $_GET['query'];

                // Sanitize the input to prevent SQL injection
                $searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8');

                // Prepare the SQL query to search for the document title
                $sql = "SELECT * FROM students WHERE studentid LIKE '%$searchQuery%' or username LIKE '%$searchQuery%'";

                // Execute the query
                $query_run = mysqli_query($con, $sql);

                // Check if there are any results
                if (mysqli_num_rows($query_run) > 0) {
                   
                    // Display the results
                    foreach($query_run as $items)
                    {
                        ?>
                         <div class="card mb" >
                        <div class="row">
<div style="padding-left: 25%; padding-top:2%" >
<img style="border-radius: 50%;border: 2px solid gray; " src="studentimages/<?php echo htmlentities($items['studentImage']);?>" width="70" height="70"> </div>
<div style="padding-top: 2%; "><h4><a href="AdminSearchStudentViewDetails.php?user=<?php echo $items['studentid']?>"><?= $items['username']; ?></a></h4> <h5><?= $items['studentid']; ?></h5><br></div>

                        </div>
                         </div>                                                                
                                                <?php
                                            }  
                                        }
                                        
                                        else
                                        {
                                            ?>
       
       <div class="row"> <h1 style="font-family: cursive;padding-top:1%;padding-left:12%" >No Record Found</h1<span style='font-size:100px;'>&#128542;</span></div></div>
                        <button type="button" class="btn btn-default" data-dismiss="modal" ><a href="AdminMainPage.php">Back</a></button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                                           <?php
                                        }
                                    }
                                     
                                ?>
                                 
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
