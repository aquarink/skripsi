<?php
if (isset($_SESSION['userId'])) {
  // Sessi Ada
  $idStaff = $_SESSION['userId'];
  include_once 'p_controller/database/connection.php';
  $dataUserBySesion = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff'");
  $fetchDataUserBySesion = mysql_fetch_assoc($dataUserBySesion);
} else {
  header("Location: ?");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Upload Your File</title>

  <link href="p_layout/css/bootstrap.min.css" rel="stylesheet">
  <link href="p_layout/css/navbar-fixed-top.css" rel="stylesheet">
  <script type="text/javascript">
  function onload() {
    $("#datas").load("?p=data&id=1");
  }
  </script>
</head>

<body onload="onload();">

  <nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">JNY ARCHIVE</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
          <li><a href="?p=home">Shared File</a></li>
          <li><a href="?p=uploadfile">Upload File</a></li>
          <li>
            <a href="?p=myfile">
              <?php
              if (isset($fetchDataUserBySesion)) {
                echo ucfirst($fetchDataUserBySesion['nama']);
              }
              ?> File
            </a>
          </li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
          <li><a href="?p=notif">Notification <b id="datas" style="color:red">0</b></a></li>
          <li class="dropdown active">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">Change Password</a></li>
              <?php
              if (isset($fetchDataUserBySesion['departemen'])) {
                if ($fetchDataUserBySesion['departemen'] == 2) {
                  echo '<li><a href="?p=staff">Data Staff</a></li>';
                }
              }
              ?>
            </ul>
          </li>
          <li><a href="?p=out">Sign Out</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <div class="page-header">
          <h1>Add New Staff</h1>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <form action="?p=savestaff" name="form" id="forms" class="form-horizontal" method="POST">
        <div class="panel-body">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-copyright-mark"></i></span>
            <input id="user" type="number" class="form-control" name="nik" value="" placeholder="Please Fill the ID (NIK)">
          </div>
        </div>
        <div class="panel-body">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input id="user" type="text" class="form-control" name="nama" value="" placeholder="Please Fill the Name">
          </div>
        </div>
        <div class="panel-body">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-chevron-up"></i></span>
            <input id="user" type="text" class="form-control" name="posisi" value="" placeholder="Please Fill the Potition">
          </div>
        </div>
        <div class="panel-body">
          <div class="input-group">
            <select id="dept" name="dept">
              <option>Chose Status Deptartment</option>
              <option value="1">New Department</option>
              <option value="2">Department Exist</option>
            </select>
          </div>
        </div>
        <div id="newDep" class="panel-body">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-chevron-up"></i></span>
            <input id="user" type="text" class="form-control" name="newDept" value="" placeholder="Please Fill New Departement">
          </div>
        </div>
        <div id="oldDep" class="panel-body">
          <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-copyright-mark"> Department</i></span>
            <select name="oldDept" class="form-control">
              <?php
              $dept = mysql_query("SELECT * FROM tb_departemen");
              while ($fetchDept = mysql_fetch_array($dept)) {
                echo '<option value="' . $fetchDept['id_dep'] . '">' . ucfirst($fetchDept['nama_dep']) . '</option>';
              }
              ?>

            </select>
          </div>
        </div>

        <div class="panel-body">
          <div class="form-group">
            <!-- Button -->
            <div class="col-sm-12 controls">
              <input name="setuju" type="checkbox" onClick="Javascript:dis(this, 1);"> (check for add new staff)
              <button id="off" onclick="uploadFile();" name="add" disabled="disabled" type="submit" href="#" class="btn btn-primary">
                <i class="glyphicon glyphicon-save"></i> Save
              </button>
            </div>
          </div>
        </div>
      </form>

      <?php
      if (isset($pesanError)) {
        echo '<center><b style="color:red">' . $pesanError . '</b></center>';
      } else {
        echo '<center><b style="color:green">Please fill the field</b></center>';
      }
      ?>

    </div>
  </div>
  <script src="p_layout/js/jquery.min.js"></script>
  <script src="p_layout/js/bootstrap.min.js"></script>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.js"></script>
  <script src="http://malsup.github.com/jquery.form.js"></script>
  <script src="p_layout/js/pebri.js"></script>
  <script src="p_layout/js/notif.js"></script>
  <script src="p_layout/js/jquery-1.8.3.min.js"></script>

  <script type="text/javascript">
  $("#dept").change(function() {
    dpt = $(this).val();
    if(dpt == 'new') {
      $("#old").hide();
    } else if(dpt == 'old') {
      $("#new").hide();
    } else {
      $("#old").hide();
      $("#new").hide();
    }
  });
  $("#old").hide();
  </script>
</body>
</html>
