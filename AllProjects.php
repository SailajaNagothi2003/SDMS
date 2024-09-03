<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']) == 0) {
    header('location:logout.php');
  } 
// Handle project deletion
if(isset($_GET['del'])) {
    mysqli_query($con, "DELETE FROM projects WHERE title = '".$_GET['name']."'");
    $_SESSION['delmsg'] = "Project deleted!!";
}
?>

<?php @include("includes/head.php"); ?>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php @include("includes/header.php"); ?>
        <?php @include("includes/student_sidebar.php"); ?>
        <div class="content-wrapper">
            <br>
            <div class="col-lg-15" style="padding-left: 15%; padding-right: 15%;">
                <div class="card mb-15">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h3 class="m-0 font-weight-bold text-primary">My Projects</h3>
                    </div>
                </div>
                
                <div class="card-body">
                    <?php
                    $id = $_SESSION['sid'];
                    $ret2 = mysqli_query($con, "SELECT * FROM projects WHERE studentid='$id'");
                    
                    if (mysqli_num_rows($ret2) == 0) {
                        echo '<h3 style="fontsize:24px; color:black;text-align:center;font-weight:bold;">Projects: 0 &#128532;</h3>';
                    } else {
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret2)) {
                            ?> 
                            <div class="card mb">
                                <br>
                                <h3 style="padding-left: 1%;"><b><?php echo htmlentities($cnt); ?>)</b>&nbsp;<b style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;color:darkblue"><?php echo htmlentities($row['title']); ?></b></h3>
                                <div style="padding-left: 4%;"> 
                                    <h5><b>Description:</b>&nbsp;<?php echo htmlentities($row['description']); ?></h5>
                                    <h5><b>Domain:</b>&nbsp;<?php echo htmlentities($row['domain']); ?></h5>
                                    <h5><b>Mentor:</b>&nbsp;<?php echo htmlentities($row['mentor']); ?></h5>
                                    <h5><b>Project Type:</b>&nbsp;<?php echo htmlentities($row['type']); ?></h5>
                                    <?php if ($row['type'] == 'Group') {
                                        echo "<h5><b>Team Members:</b></h5>";
                                        $members = explode(",", $row['members']);
                                        echo "<ul>";
                                        foreach ($members as $member) {
                                            echo "<li>" . htmlspecialchars(trim($member)) . "</li>";
                                        }
                                        echo "</ul>";
                                    } ?>
                                    <iframe src="projectimages/<?php echo htmlentities($row['doc']); ?>" width="750" height="350"></iframe>
                                    <div style="padding-top: 1%; padding-left: 80%;"> 
                                        <button class="btn btn-success btn-xs edit_data2" data-toggle="modal" data-target="#viewDocumentModal" data-doc="projectimages/<?php echo htmlentities($row['doc']); ?>"><h5>View</h5></button>
                                        <a href="AllProjects.php?name=<?php echo $row['title']; ?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-danger btn-xs"><h5>Delete</h5></a>
                                    </div>
                                    <p></p>
                                </div>
                            </div>
                            <br>
                            <?php 
                            $cnt++;
                        } 
                    }
                    ?>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>
    <!-- /.content-wrapper -->

    <!-- Modal -->
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
    <?php @include("includes/footer.php"); ?>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#viewDocumentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var docUrl = button.data('doc'); // Extract info from data-* attributes
                var modal = $(this);
                modal.find('#modalDocument').attr('src', docUrl);
            });
        });
    </script>
</body>
</html>
