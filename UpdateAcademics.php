<?php 
session_start();
error_reporting(0);
include('includes/dbconnection.php');

if (strlen($_SESSION['sid'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_POST['submit'])) {
        $eid = $_SESSION['sid'];
        $spec = $_POST['spec'];
        $end_year = $_POST['end_year'];
        $start_year = $_POST['start_year'];
        $marks = $_POST['marks'];
        $clgname = $_POST['clgname'];

        // Validation (e.g., ensure all fields are filled)
        if (empty($spec) || empty($end_year) || empty($start_year) || empty($marks) || empty($clgname)) {
            $error[] = "All fields are required.";
        } else if (!is_numeric($marks) || !is_numeric($start_year) || !is_numeric($end_year)) {
            $error[] = "Marks and years must be numeric.";
        } else {
            $sql = "UPDATE students SET spec=:spec, start_year=:start_year, end_year=:end_year, marks=:marks, clgname=:clgname WHERE studentid=:eid";
            $query = $dbh->prepare($sql);
            $query->bindParam(':spec', $spec, PDO::PARAM_STR);
            $query->bindParam(':start_year', $start_year, PDO::PARAM_STR);
            $query->bindParam(':end_year', $end_year, PDO::PARAM_STR);
            $query->bindParam(':clgname', $clgname, PDO::PARAM_STR);
            $query->bindParam(':marks', $marks, PDO::PARAM_STR);
            $query->bindParam(':eid', $eid, PDO::PARAM_STR);
            if ($query->execute()) {
                $success[] = "Profile updated successfully!";
                $redirect = true; // Set redirection flag
            } else {
                $error[] = "An error occurred while updating the profile.";
            }
        }
    }
?>

<?php @include("includes/head.php"); ?>

<body class="hold-transition sidebar-mini layout-fixed">
    <style>
        .error-msg {
            margin: 10px 0;
            display: block;
            background: crimson;
            color: #fff;
            border-radius: 5px;
            font-size: 18px;
            padding: 10px;
            text-align: center;
        }

        .success-msg {
            margin: 10px 0;
            display: block;
            background: green;
            color: #fff;
            border-radius: 5px;
            font-size: 18px;
            padding: 10px;
            text-align: center;
        }
    </style>
    <div class="wrapper">
        <?php @include("includes/header.php"); ?>
        <?php @include("includes/student_sidebar.php"); ?>

        <div class="content-wrapper">
            <br>
            <div class="col-lg-12">
                <div class="card mb-4">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h4 class="m-0 font-weight-bold text-primary">Update Academics</h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($error)) {
                            foreach ($error as $errorMsg) {
                                echo '<span class="error-msg">' . $errorMsg . '</span>';
                            }
                        }

                        if (isset($success)) {
                            foreach ($success as $successMsg) {
                                echo '<span class="success-msg">' . $successMsg . '</span>';
                            }
                            if (isset($redirect)) {
                                echo "<script>
                                    setTimeout(function() {
                                        window.location.href = 'UpdateAcademics.php';
                                    }, 1500); // 1.5 seconds delay
                                </script>";
                            }
                        }
                        ?>

                        <form method="post">
                            <?php
                            $eid = $_SESSION['sid'];
                            $sql = "SELECT * FROM students WHERE studentid=:eid";                                    
                            $query = $dbh->prepare($sql);
                            $query->bindParam(':eid', $eid, PDO::PARAM_STR);
                            $query->execute();
                            $results = $query->fetchAll(PDO::FETCH_OBJ);

                            if ($query->rowCount() > 0) {
                                foreach ($results as $row) {    
                                    ?>
                                    <div class="container rounded bg-white mt-5">
                                        <div class="row">
                                            <div class="col-md-4 border-right">
                                                <div class="d-flex flex-column align-items-center text-center p-3 py-5">
                                                    <div style="padding-top: 10%;">
                                                    <i class='fas fa-school' style="font-size: 150px; color: darkblue;"></i>
                                                    </div> 
                                                    <div style="padding-top: 5%;">
                                                        <span style="color:blue; font-family:cursive;font-size:20px"><b><?php echo strtoupper($row->studentid); ?></b></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="p-3 py-5">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <div class="d-flex flex-row align-items-center back">
                                                            <i></i>
                                                        </div>
                                                        <h6 class="text-right">Edit Academics</h6>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-6">
                                                            <label>StudentId</label>
                                                            <input type="text" class="form-control" name="studentid" value="<?php echo $row->studentid; ?>" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Specialisation</label>
                                                            <input type="text" class="form-control" name="spec" value="<?php echo $row->spec; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-6">
                                                            <label>College Name</label>
                                                            <input type="text" class="form-control" name="clgname" value="<?php echo $row->clgname; ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Academic Percentage</label>
                                                            <input type="number" class="form-control" name="marks" value="<?php echo $row->marks; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-6">
                                                            <label>Year of Entry</label>
                                                            <input type="number" class="form-control" name="start_year" value="<?php echo $row->start_year; ?>">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Year of Graduation</label>
                                                            <input type="number" class="form-control" name="end_year" value="<?php echo $row->end_year; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer text-right">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    <!-- Go Back Button -->
                                    <button type="button" class="btn btn-secondary" onclick="window.history.back();">Go Back</button>
                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php 
                                }
                            } ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- /.content-header -->
        </div>
        <!-- /.content-wrapper -->
        <?php @include("includes/foot.php"); ?>
        <?php @include("includes/footer.php"); ?>
    </div>
</body>
</html>
<?php } ?>
