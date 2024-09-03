
<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']==0)) {
    header('location:logout.php');
    } 
if(isset($_GET['del'])) {
    mysqli_query($con, "DELETE FROM projects WHERE title = '".$_GET['name']."'");
    $_SESSION['delmsg'] = "Project deleted !!";
}
$pid = $_SESSION['id'];
$user_role = $_SESSION['role']; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include("includes/head.php"); ?>
    <style>
        /* Style for visibility of username link */
        .username-link {
            text-decoration: none; /* Remove underline */
            color: #007bff; /* Change color to blue */
            font-weight: bold; /* Make it bold */
        }

        .username-link:hover {
            text-decoration: underline; /* Underline on hover */
        }

        /* Prevent hover effect on other descriptions */
        .description-text {
            color: #000; /* Default color */
        }
    </style>
</head>
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
        <div class="content-wrapper">
            <br>
            <div class="col-lg-15" style="padding-left: 15%; padding-right: 15%;">
                <div class="card mb-15">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h3 class="m-0 font-weight-bold text-primary">Projects</h3>
                    </div>
                </div>

                <div class="card-body">
                    <?php
                    $id = $_SESSION['sid'];
                    $ret2 = mysqli_query($con, "SELECT * FROM projects ORDER BY id DESC");
                    $cnt = 1; // Initialize $cnt variable

                    while ($row = mysqli_fetch_array($ret2)) {
                    ?> 
                    <div class="card mb">
                        <br>
                        <div style="padding-left: 3%;">
                        <a href="AdminSearchStudentViewDetails.php?user=<?php echo $row['studentid']; ?>" class="username-link">
                            <h3><?php echo $row['studentid']; ?></h3>
                        </a>
                        </div>
                        <div style="padding-left: 4%;"> 
                            <h5 class="description-text"><b>Title:</b>&nbsp;<?php echo htmlentities($row['title']);?></h5> 
                            <h5 class="description-text"><b>Description:</b>&nbsp;<?php echo htmlentities($row['description']);?></h5>
                            <h5 class="description-text"><b>Domain:</b>&nbsp;<?php echo htmlentities($row['domain']);?></h5>
                            <h5 class="description-text"><b>Mentor:</b>&nbsp;<?php echo htmlentities($row['mentor']);?></h5>
                            <h5 class="description-text"><b>ProjectType:</b>&nbsp;<?php echo htmlentities($row['type']);?></h5>
                            <?php if ($row['type'] == 'Group') {
                                echo "<h5 class='description-text'><b>Team Members:</b></h5>";
                                $members = explode(",", $row['members']);
                                echo "<ul>";
                                foreach ($members as $member) {
                                    echo "<li>" . htmlspecialchars(trim($member)) . "</li>";
                                }
                                echo "</ul>";
                            }?>
                            <div style="padding-right: 5%;">
                                <iframe src="projectimages/<?php echo htmlentities($row['doc']);?>" width="100%" height="350"></iframe>
                            </div>
                            <div style="padding-top: 1%; padding-left:88%">
                                <button class="btn btn-success btn-xs view_data2" data-toggle="modal" data-target="#viewDocumentModal" data-doc="projectimages/<?php echo htmlentities($row['doc']); ?>" title="Click to view document">
                                    <h5>View</h5>
                                </button>
                            </div>
                            <p></p>
                        </div>
                    </div>
                    <br>
                    <?php $cnt++; } ?>
                </div>
            </div>
        </div>   

        <!-- /.content-header -->
    </div>
    <!-- /.content-wrapper -->
    
    <!-- Modal for viewing documents -->
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

    <?php include("includes/foot.php"); ?>
    <?php include("includes/footer.php"); ?>
</body>
</html>

<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '.view_data2', function(){
            var docUrl = $(this).data('doc'); // Get document URL from data attribute
            $('#modalDocument').attr('src', docUrl); // Set iframe source
            $('#viewDocumentModal').modal('show'); // Show modal
        });
    });
</script>
