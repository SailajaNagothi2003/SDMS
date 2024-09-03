<?php

session_start();
error_reporting(0);
include('includes/dbconnection.php');

?>
<div class=" row card-body">
  <?php
  $eid2=$_POST['edit_id2'];
  $ret2=mysqli_query($con,"select * from  students where studentid='$eid2'");
  while ($row=mysqli_fetch_array($ret2))
  {
    ?> 
    <div class="col-md-4">
      <img src="studentimages/<?php echo htmlentities($row['studentImage']);?>" width="100" height="100" style="border:2px solid gray">
    </div>
    <div class="col-md-8">
      <table>
         <tr>
          <th>StudentID</th>
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
    <?php 
  } ?>
</div>