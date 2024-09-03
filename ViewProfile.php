<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
    header('location:logout.php');
    } 
    
// Fetch user role from session or database
$id = $_SESSION['sid'];
$ret2 = mysqli_query($con, "SELECT * FROM students WHERE studentid='$id'");
$user = mysqli_fetch_array($ret2);
$role = $user['user']; // Assume 'user' column holds the role (e.g., 'admin' or 'student')

?>

<!DOCTYPE html>
<html>
    <style>
        tr {
            padding-left: 8%;
        }
        th {
            font-size: 24px;
        }
        td {
            font-size: 18px;
        }
    </style>
<head>
    <?php include("includes/head.php"); ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar and Sidebar based on role -->
        <?php 
        if ($role == 'Admin') {
            include("includes/header.php"); 
            include("includes/sidebar.php");
        } else {
            include("includes/header.php"); 
            include("includes/student_sidebar.php");
        }
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="padding-left: 20%;" >
            <br>
            <div class="col-lg-7">
                <div class="card mb-5">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary"> User Profile</h4>
                    </div>
                    <div class="card-body">
                    <?php
                    // Display user details
                    $ret2 = mysqli_query($con, "SELECT * FROM students WHERE studentid='$id'");
                    while ($row = mysqli_fetch_array($ret2)) {
                    ?> 
                    <div class="row">
                        <!-- User details on the left -->
                        <div class="col-md-8">
                            <table>
                                <tr>
                                    <th>ID</th>
                                    <td>&nbsp;<?php  echo $row['studentid'];?></td>
                                </tr>
                                <tr>
                                    <th>UserName</th>
                                    <td><?php  echo $row['username'];?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php  echo $row['email'];?></td>
                                </tr>
                                <tr>
                                    <th>College</th>
                                    <td><?php  echo $row['clgname'];?></td>
                                </tr>
                                <tr>
                                    <th>Branch</th>
                                    <td><?php  echo $row['spec'];?></td>
                                </tr>
                                <tr>
                                    <th>Year</th>
                                    <td><?php  echo $row['start_year']."-".$row['end_year'];?></td>
                                </tr>
                            </table>
                        </div>

                        <!-- User image on the right -->
                        <div class="col-md-4 text-right" style="padding-right: 10%;position:relative">
                            <img src="studentimages/<?php echo htmlentities($row['studentImage']);?>" width="160px" height="160px" style="border:2px solid gray;">
                        </div>
                    </div>
                    <?php 
                    } ?>
                    </div>
                </div>
            </div>

            <!-- /.content-header -->
        </div>
        <!-- /.content-wrapper -->

        <?php 
        include("includes/foot.php"); 
        include("includes/footer.php"); 
        ?>
    </div>
</body>
</html>
