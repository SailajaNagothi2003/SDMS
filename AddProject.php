<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['sid']) == 0) {
  header('location:logout.php');
} else {
  if (isset($_POST['submit'])) {
    $studentid = $_SESSION['sid'];
    $branch=$_SESSION['spec'];
    $username = $_SESSION['username'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $domain = $_POST['domain'];
    $mentor = $_POST['mentor'];
    $projectType = $_POST['projectType'];
    $memberNames = isset($_POST['memberName']) ? $_POST['memberName'] : [];
    $year = $_POST['year'];
    $doc = $_FILES["doc"]["name"];
    move_uploaded_file($_FILES["doc"]["tmp_name"],"projectimages/".$_FILES["doc"]["name"]);
    $titleCheckQuery = mysqli_query($con, "SELECT * FROM projects WHERE title='$title' AND studentid='$studentid'");
    if (mysqli_num_rows($titleCheckQuery) > 0) {
        $error[] = "A project with this title already exists. Please choose a different title.";
    } else {
    if ($doc) {
        $target_dir = "projectimages/";
        $target_file = $target_dir . basename($doc);
        $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if ($fileType != "pdf") { 
            $error[] = "Sorry, only PDF files are allowed.";
        }
        else if ($_FILES["doc"]["size"] > 1000000) { // 1MB limit
            
            $error[] = "Sorry, your file is too large.";
        }
    
else{
    $memberNamesString = implode(', ', array_filter($memberNames));

    $query = mysqli_query($con, "INSERT INTO projects(studentid,username,branch, title, description, domain, mentor, type, members, year, doc) VALUES('$studentid','$username','$branch', '$title', '$description', '$domain', '$mentor', '$projectType', '$memberNamesString', '$year', '$doc')");

    if ($query) {
        $success[] = "Project Submitted Successfully.";
        $redirect = true;
    } else {
        $error[] = "Something Went Wrong. Please try again.";    
    }
  }
}
  }
}
?>
<!DOCTYPE html>
<html>
<style>
    .error-msg {
        margin: 10px 0;
        display: block;
        background: crimson;
        color: #fff;
        border-radius: 5px;
        font-size: 20px;
        padding: 10px;
        text-align: center;
    }

    .success-msg {
        margin: 10px 0;
        display: block;
        background: green;
        color: #fff;
        border-radius: 5px;
        font-size: 20px;
        padding: 10px;
        text-align: center;
    }

    .required-field:after {
        content: '*';
        color: red;
        margin-left: 5px;
    }
</style>
  <script>
    function ProjectType() {
      if (document.getElementById('group').checked) {
        document.getElementById('yes').style.visibility = 'visible';
      } else {
        document.getElementById('yes').style.visibility = 'hidden';
      }
    }
  </script>
  <style> 
    .content {
      padding-left: 5%;
    }
    .hidden {
      display: none;
    }
    .removeMemberButton {
      background: none;
      border: none;
      color: red;
      font-size: 1.2em;
      cursor: pointer;
      margin-left: 10px;
    }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const projectTypeRadios = document.getElementsByName("projectType");
      const groupMembersSection = document.getElementById("groupMembersSection");
      const addMemberButton = document.getElementById("addMemberButton");
      const groupMembersContainer = document.getElementById("groupMembersContainer");
      let memberCount = <?php echo isset($memberCount) ? $memberCount : 1; ?>;

      projectTypeRadios.forEach(radio => {
        radio.addEventListener("change", () => {
          if (radio.value === "Group" && radio.checked) {
            groupMembersSection.style.display = "block";
          } else {
            groupMembersSection.style.display = "none";
          }
        });
      });

      addMemberButton.addEventListener("click", () => {
        memberCount++;
        const newMemberInput = document.createElement("div");
        newMemberInput.classList.add("member-input");
        newMemberInput.innerHTML = `
          <label for="memberName${memberCount}">Member Name:</label>
          <input type="text" id="memberName${memberCount}" name="memberName[]">
          <button type="button" class="removeMemberButton">&times;</button>
        `;
        groupMembersContainer.appendChild(newMemberInput);
      });

      groupMembersContainer.addEventListener("click", (event) => {
        if (event.target.classList.contains("removeMemberButton")) {
          event.target.parentElement.remove();
        }
      });
    });
  </script>
  <?php @include("includes/head.php"); ?>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <?php @include("includes/header.php"); ?>
      <?php @include("includes/student_sidebar.php"); ?>
      <div class="content-wrapper">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item"><a href="MainPage.php"><i class="fas fa-home"></i>Home</a></li>
                </ol>
              </div>
            </div>
          </div>
        </section>
        <section class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-header">
                    <h3 class="card-title">Add Project</h3>
                  </div>
                  <form role="form" method="post" enctype="multipart/form-data">
                    <div class="card-body">
                      <center><span style="color: brown"><h4>Project details</h4></span></center>
                      <hr>
                      <?php
if (isset($error)) {
    foreach ($error as $error) {
        echo '<span class="error-msg">'.$error.'</span>';
    }
}

if (isset($success)) {
    foreach ($success as $msg) {
        echo '<span class="success-msg">'.$msg.'</span>';
    }
    if ($redirect) {
        echo "<script>
            setTimeout(function() {
                window.location.href = 'AddProject.php';
            }, 1500); // 1.5 seconds
        </script>";
    }
}
?>
                      <div class="row" style="font-size: 20px;">
                        <div class="form-group col-md-10" style="padding-left: 10%;">
                          <label for="title" class="required-field">Project Title</label>
                          <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                        </div>
                        <div class="form-group col-md-10" style="padding-left: 10%;">
                          <label for="description" class="required-field">Project Description</label>
                          <textarea rows="4" cols="50" class="form-control" id="description" name="description" placeholder="Explain it in 50 words" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                        </div>
                        <div class="form-group col-md-10" style="padding-left: 10%;">
                          <label for="domain" class="required-field">Domain</label>
                          <input type="text" class="form-control" id="domain" name="domain" placeholder="i.e AIML,Web Development..." value="<?php echo isset($_POST['domain']) ? htmlspecialchars($_POST['domain']) : ''; ?>" required>
                        </div>
                        
                        <div class="form-group col-md-10" style="padding-left: 10%;">
                          <label for="mentor" class="required-field">Project Mentor</label>
                          <input type="text" class="form-control" id="mentor" name="mentor" placeholder="mentor" value="<?php echo isset($_POST['mentor']) ? htmlspecialchars($_POST['mentor']) : ''; ?>" required>
                        </div>
                        <div class="form-group col-md-10" style="padding-left: 10%;">
                          <label for="projectType" class="required-field">Project Type</label><br>
                          <input type="radio" id="individual" name="projectType" value="Individual" required>
                          <label for="individual">Individual&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                          <input type="radio" id="group" name="projectType" value="Group"  required>
                          <label for="group">Group</label>
                        </div>
                        <div class="form-group col-md-10 hidden" id="groupMembersSection" style="padding-left: 10%;">
                          <label for="members">Group Members</label>
                          <div id="groupMembersContainer">
                            <!-- JavaScript will add member fields here -->
                            <div class="member-input">
                              <label for="memberName1">Member Name:</label>
                              <input type="text" id="memberName1" name="memberName[]">
                            </div>
                          </div>
                          <button type="button" id="addMemberButton">Add Member</button>
                        </div>
                        <div class="form-group col-md-10" style="padding-left: 10%;">
    <label for="year" class="required-field">Choose Project Term</label>
    <select type="select" class="form-control" id="year" name="year" required>
        <option value="" >Select Semester</option>
        <option value="1" <?php echo isset($_POST['year']) && $_POST['year'] == '1' ? 'selected' : ''; ?>>1-1</option>
        <option value="2" <?php echo isset($_POST['year']) && $_POST['year'] == '2' ? 'selected' : ''; ?>>1-2</option>
        <option value="3" <?php echo isset($_POST['year']) && $_POST['year'] == '3' ? 'selected' : ''; ?>>2-1</option>
        <option value="4" <?php echo isset($_POST['year']) && $_POST['year'] == '4' ? 'selected' : ''; ?>>2-2</option>
        <option value="5" <?php echo isset($_POST['year']) && $_POST['year'] == '5' ? 'selected' : ''; ?>>3-1</option>
        <option value="6" <?php echo isset($_POST['year']) && $_POST['year'] == '6' ? 'selected' : ''; ?>>3-2</option>
        <option value="7" <?php echo isset($_POST['year']) && $_POST['year'] == '7' ? 'selected' : ''; ?>>4-1</option>
        <option value="8" <?php echo isset($_POST['year']) && $_POST['year'] == '8' ? 'selected' : ''; ?>>4-2</option>
    </select>
</div>
                        <div class="form-group col-md-10" style="padding-left: 10%;">
                          <label for="doc" class="required-field" >Upload Document (PDF only)</label>
                          <input type="file" class="form-control-file" id="doc" name="doc" required>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer" style="padding-right: 18%;">
                                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
      <?php @include("includes/footer.php"); ?>
    </div>
    <?php @include("includes/foot.php"); ?>
  </body>
  <?php } ?>
</html>
