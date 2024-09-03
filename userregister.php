<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if the user is logged in
if (!isset($_SESSION['sid']) || strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
    exit;
}

// Block user functionality
if (isset($_GET['delid'])) {
    $rid = intval($_GET['delid']);
    $sql = "UPDATE students SET status = '0' WHERE id = :rid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':rid', $rid, PDO::PARAM_INT);
    $query->execute();
    echo "<script>alert('Blocked successfully');</script>"; 
    echo "<script>window.location.href = 'userregister.php'</script>";
    exit;
}
?>

<?php include("includes/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include("includes/header.php"); ?>
        <!-- Main Sidebar Container -->
        <?php include("includes/sidebar.php"); ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <!-- <h1>DataTables</h1> -->
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="MainPage.php"><i class="fas fa-home"></i> Home</a></li>
                                <li class="breadcrumb-item active">User Register</li>
                            </ol>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Statistics Boxes -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <?php
                                    $sql = "SELECT id FROM students";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $count = $query->rowCount();
                                    ?>
                                    <h3><?php echo htmlentities($count); ?></h3>
                                    <p>Total Users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <?php
                                    $sql = "SELECT id FROM students WHERE gender = 'Male'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $count = $query->rowCount();
                                    ?>
                                    <h3><?php echo htmlentities($count); ?></h3>
                                    <p>Male</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <?php
                                    $sql = "SELECT id FROM students WHERE gender = 'Female'";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $count = $query->rowCount();
                                    ?>
                                    <h3><?php echo htmlentities($count); ?></h3>
                                    <p>Female</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <?php
                                    $sql = "SELECT id FROM students WHERE status = 1";
                                    $query = $dbh->prepare($sql);
                                    $query->execute();
                                    $count = $query->rowCount();
                                    ?>
                                    <h3><?php echo htmlentities($count); ?></h3>
                                    <p>Active Users</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Registered Users</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-primary" >
                                            <i class="fas fa-plus"></i><a href="AddStudent.php" style="color: white;" > Add User</a>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#delete">
                                            See Blocked Users
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                               <th class="text-center">S.NO</th>
                                                <th class="text-center">StudentID</th>
                                                <th class="text-center">Name</th>
                                                <th class="text-center">Mobile</th>
                                                <th class="text-center">Email</th>
                                                <th class="text-center">Permission</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = "SELECT * FROM students WHERE status = 1 and studentid!='Admin'";
                                            $query = $dbh->prepare($sql);
                                            $query->execute();
                                            $results = $query->fetchAll(PDO::FETCH_OBJ);
                                            $cnt=1;

                                            if ($query->rowCount() > 0) {
                                                foreach ($results as $row) {
                                            ?>
                                                    <tr>
                                                    <td class="text-left"><?php echo $cnt; ?></td>
                                                    <td class="text-left"><?php echo htmlentities($row->studentid); ?></td>
                                                        <td class="text-left"><?php echo htmlentities($row->fname); ?> <?php echo htmlentities($row->lname); ?></td>
                                                        <td class="text-left"><?php echo htmlentities($row->number); ?></td>
                                                        <td class="text-left"><?php echo htmlentities($row->email); ?></td>
                                                        <td class="text-left"><?php echo htmlentities($row->user); ?></td>
                                                        <td class="text-left">
                                                        <a class="edit_data" id="<?php echo  ($row->id); ?>" title="click for edit"><i class="fas fa-edit"></i></a>
                                                        <a href="userregister.php?delid=<?php echo ($row->id);?>" title="click for block" onclick="return confirm('sure to block ?')" >Block</i></a>
                                                         </td>
                                                    </tr>
                                            <?php
                                            $cnt=$cnt+1;
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <!-- Footer -->
        <?php include("includes/footer.php"); ?>
    </div>

    <!-- Scripts -->
    <?php include("includes/scripts.php"); ?>

    <!-- AJAX Script for Editing User -->
    <script>
        $(document).on('click', '.edit_data', function(){
            var id = $(this).attr('id');
            $.ajax({
                url: "edituser.php",
                method: "POST",
                data: { id: id },
                success: function(data){
                    $('#editData').modal('show');
                    $('#info_update').html(data);
                }
            });
        });
    </script>

    <!-- Modal for Adding User -->
    <div class="modal fade" id="addUserModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add New User</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include("AddStudent.php"); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Viewing Blocked Users -->
    <div class="modal fade" id="delete">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Blocked Users</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php include("blockedusers.php"); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

   
</body>
</html>
