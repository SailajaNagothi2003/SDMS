 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="dashboard.php" class="brand-link">
    <img src="company/logo.jpg" alt="Leading Estate" class="brand-image img-circle elevation-3"
    style="opacity: .8">
    <span class="brand-text font-weight-light">GVPCEW</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <?php
      $eid=$_SESSION['sid'];
      $sql="SELECT * from  students where studentid=:eid ";                                    
      $query = $dbh -> prepare($sql);
      $query-> bindParam(':eid', $eid, PDO::PARAM_STR);
      $query->execute();
      $results=$query->fetchAll(PDO::FETCH_OBJ);

      $cnt=1;
      if($query->rowCount() > 0)
      {
        foreach($results as $row)
        {    
          ?>
          <div class="image">
            <img class="img-circle"
            src="studentimages/<?php echo htmlentities($row->studentImage);?>" class="user-image" 
            alt="">
          </div>
          <div class="info">
          <a href="StudentDashboard.php?user=<?php echo $row->studentid; ?>"  class="d-block">
    <h5><?php echo $row->username; ?></h5>
</a>
          </div>
          <?php 
        }
      } ?>

    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item has-treeview menu-open">
          <a href="MainPage.php" class="nav-link active">
            <i class="fas fa-home"  style="font-size: 25px;"></i>
            <p>
              Home
            </p>
          </a>
        </li>
        <li class="nav-item ">
        <a href="StudentDashboard.php?user=<?php echo $row->studentid; ?>"  class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>
              Dashboard
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="UpdateProfile.php" class="nav-link">
            <i class="nav-icon far fa fa-user"></i>
            <p>
              User Profile
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="UpdateImage.php" class="nav-link">
            <i class="nav-icon fa fa-image"></i>
            <p>
              Edit Image
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="UpdateAcademics.php" class="nav-link">
            <i class="nav-icon fas fa-book-reader"></i>
            <p>
              Update Academics
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="AddProject.php" class="nav-link">
            <i class="nav-icon far fa-plus-square"></i>
            <p>
              Add Project
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="AllProjects.php" class="nav-link">
            <i class="nav-icon far fas fa-project-diagram"></i>
            <p>
              View Projects
            </p>
          </a>
        </li>


        <li class="nav-header">SETTINGS</li>
        <!-- User Menu -->
        <li class="nav-item">
          <a href="UpdatePassword.php" class="nav-link">
           <i class="fas fa-unlock nav-icon"></i>
           <p>
            Change Password
          </p>
        </a>
      </li>
        <li class="nav-item">
          <a href="logout.php" class="nav-link">
           <i class="fas fa-arrow-alt-circle-right nav-icon"></i>
           <p>
            Logout
          </p>
        </a>
      </li><!-- /.user menu -->
    </ul>
  </nav>
  <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
</aside>
<style>
/* Ensure the profile image fits properly */
.user-panel .image img {
  width: 45px;  /* Fixed width */
  height: 45px; /* Fixed height */
  object-fit: cover; /* Ensures the image maintains its aspect ratio and covers the box */
  border-radius: 50%; /* Circular image */
}
</style>