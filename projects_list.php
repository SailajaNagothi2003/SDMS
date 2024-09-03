<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');

// Check if user is logged in
if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
    exit;
}

// Handle deletion of a project
if (isset($_GET['del'])) {
    $title = mysqli_real_escape_string($con, $_GET['del']); // Sanitize input

    // Execute delete query
    $query = "DELETE FROM projects WHERE title = '$title'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $_SESSION['delmsg'] = "Project deleted successfully!";
    } else {
        $_SESSION['delmsg'] = "Error deleting project: " . mysqli_error($con);
    }
}
?>
<!DOCTYPE html>
<html>
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
              <h2>Project Details</h2>
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
          <div class="row">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Manage Students</h3>
                </div>
                <!-- /.card-header -->
           
                <div id="editData2" class="modal fade">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title">Student Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body" id="info_update2">
                        <?php @include("view_student_info.php");?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <!-- /.modal -->
                </div>
                <!--   end modal -->

                <div class="card-body mt-2">
                  <table id="example1" class="table table-bordered table-hover">
                    <thead> 
                      <tr> 
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Project Title</th>
                        <th>Domain</th>
                        <th>Mentor</th>
                        <th>Type</th>
                        <th>Document</th>
                        <th>Action</th>
                      </tr> 
                    </thead> 
                    <tbody>
                      <?php 
                      $query = mysqli_query($con, "SELECT * FROM projects");
                      $cnt = 1;
                      while ($row = mysqli_fetch_array($query)) {
                        ?>                  
                        <tr>
                          <td><?php echo htmlentities($cnt);?></td>
                          <td><?php echo htmlentities($row['studentid']);?></td>
                          <td><?php echo htmlentities($row['title']);?></td>
                          <td><?php echo htmlentities($row['domain']);?></td>
                          <td><?php echo htmlentities($row['mentor']);?></td>
                          <td><?php echo htmlentities($row['type']);?></td>
                          <td><?php echo htmlentities($row['doc']);?></td>
                          <td>
                            <button class="btn btn-success btn-xs edit_data2" data-toggle="modal" data-target="#viewDocumentModal" data-doc="projectimages/<?php echo htmlentities($row['doc']); ?>" title="Click to view document">View</button>
                            <a href="projects_list.php?del=<?php echo urlencode($row['title']); ?>" onClick="return confirm('Are you sure you want to delete this project?')" class="btn btn-danger btn-xs">Delete</a>
                          </td>
                        </tr>
                        <?php 
                        $cnt++;
                      } 
                      ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.card-body -->
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
    <?php @include("includes/footer.php"); ?>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>

  <!-- ./wrapper -->
  <div class="modal fade" id="viewDocumentModal" tabindex="-1" role="dialog" aria-labelledby="viewDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewDocumentModalLabel">Project Document</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <iframe id="modalDocument" src="" width="100%" height="500px"></iframe>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php @include("includes/foot.php"); ?>
  
  <script type="text/javascript">
    $(document).ready(function(){
      $(document).on('click', '.edit_data2', function(){
        var docUrl = $(this).data('doc'); // Get document URL from data attribute
        $('#modalDocument').attr('src', docUrl); // Set iframe source
        $('#viewDocumentModal').modal('show'); // Show modal
      });
    });
  </script>

</body>
</html>
