<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in, if not redirect to the logout page
if (!isset($_SESSION['sid']) || strlen($_SESSION['sid']) == 0) {
    header('location:logout.php'); // Updated to match your logout file
    exit();
}

// Assuming the session contains user role information
@include("includes/header.php"); // Changed to admin_header.php
@include("includes/sidebar.php"); // Changed to admin_sidebar.php
?>
<!DOCTYPE html>
<html>
<?php @include("includes/head.php"); ?>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Dashboard</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="MainPage.php"><i class="fas fa-home"></i> Home</a></li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <!-- Total Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <?php 
                                $query1 = mysqli_query($con, "SELECT * FROM students");
                                $totalcust = mysqli_num_rows($query1);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalcust; ?></h3>
                                    <p>Total Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- Total Male Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <?php 
                                $query2 = mysqli_query($con, "SELECT * FROM students WHERE LOWER(gender)='male'");
                                $totalmale = mysqli_num_rows($query2);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalmale; ?></h3>
                                    <p>Total Male Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-male"></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- Total Female Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <?php 
                                $query3 = mysqli_query($con, "SELECT * FROM students WHERE LOWER(gender)='female'");
                                $totalfemale = mysqli_num_rows($query3);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalfemale; ?></h3>
                                    <p>Total Female Students</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-female"></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Total CSE Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <?php 
                                $query4 = mysqli_query($con, "SELECT * FROM students WHERE LOWER(spec)='cse'");
                                $totalcse = mysqli_num_rows($query4);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalcse; ?></h3>
                                    <p>Total CSE Students</p>
                                </div>
                                <div class="icon">
                                <i style="font-size: 48PX;"><B>CSE</B></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- Total ECE Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <?php 
                                $query5 = mysqli_query($con, "SELECT * FROM students WHERE LOWER(spec)='ece'");
                                $totalece = mysqli_num_rows($query5);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalece; ?></h3>
                                    <p>Total ECE Students</p>
                                </div>
                                <div class="icon">
                                <i style="font-size: 48PX;"><B>ECE</B></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- Total CSM Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <?php 
                                $query6 = mysqli_query($con, "SELECT * FROM students WHERE LOWER(spec)='csm'");
                                $totalcsm = mysqli_num_rows($query6);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalcsm; ?></h3>
                                    <p>Total CSM Students</p>
                                </div>
                                <div class="icon">
                                <i style="font-size: 48PX;"><B>CSM</B></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.row (main row) -->
                    <div class="row">
                        <!-- Total Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <?php 
                                $query7 = mysqli_query($con, "SELECT * FROM students where LOWER(spec)='eee'");
                                $totaleee = mysqli_num_rows($query7);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totaleee; ?></h3>
                                    <p>Total EEE Students</p>
                                </div>
                                <div class="icon">
                                    <i style="font-size: 48PX;"><B>EEE</B></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- Total Male Students -->
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-success">
                                <?php 
                                $query8 = mysqli_query($con, "SELECT * FROM students WHERE LOWER(spec)='it'");
                                $totalit = mysqli_num_rows($query8);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $totalit; ?></h3>
                                    <p>Total IT Students</p>
                                </div>
                                <div class="icon">
                                <i style="font-size: 48PX;"><B>IT</B></i>
                                </div>
                                <a href="student_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-6">
                            <div class="small-box bg-info">
                                <?php 
                                $query9 = mysqli_query($con, "SELECT * FROM projects ");
                                $total= mysqli_num_rows($query9);
                                ?>
                                <div class="inner">
                                    <h3><?php echo $total; ?></h3>
                                    <p>Total Projects</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-tasks"></i>
                                </div>
                                <a href="projects_list.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                       
                        
                    </div>
                </div>
                <!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php @include("includes/footer.php"); ?>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
          <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <?php @include("includes/foot.php"); ?>
</body>
</html>
