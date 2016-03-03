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
  <title>Share My File</title>

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
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="?p=repass">Change Password</a></li>
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
          <h1>List Staff Data</h1>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-lg-offset-4">
        <input type="search" id="search" value="" class="form-control" placeholder="Search Staff or Teacher">
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <table class="table" id="table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Depatment</th>
              <th>Potition</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $id_dok = $_GET['verify'];
            $dataStaff = mysql_query("SELECT * FROM tb_staff AS st LEFT JOIN tb_share AS sh ON st.`id_staff` = sh.`staff_share` WHERE st.`staff_status`= '1' ORDER BY st.`nama` ASC");
            $i = 0;
            while ($user = mysql_fetch_array($dataStaff)) {
              ?>
              <tr>
                <td><?php echo $user['nama'] ?></td>
                <td>
                  <?php
                  $dataDepartemen = mysql_query("SELECT * FROM tb_departemen WHERE id_dep = '$user[departemen]'");
                  $fetchDept = mysql_fetch_assoc($dataDepartemen);
                  echo $fetchDept['nama_dep'];
                  ?>
                </td>
                <td><?php echo $user['jabatan'] ?></td>
                <td><a href="?p=detailstaff&staff=<?php echo $user['id_staff'] ?>" class="btn btn-warning"><i class="glyphicon glyphicon-edit"> Edit</a></td>
                </tr>
                <?php
                $i++;
              }
              ?>
            </tbody>
          </table>
          <hr>
          <a href="?p=addstaff" class="btn btn-primary">
            <i class="glyphicon glyphicon-send"></i> Add New Staff
          </a>
        </div>
      </div>
      <hr>


      <?php
      if (isset($warning)) {
        echo '<strong>' . $warning . '</strong>, ';
      }
      ?>

    </div>

    <script src="p_layout/js/jquery.min.js"></script>
    <script src="p_layout/js/bootstrap.min.js"></script>
    <script src="p_layout/js/pebri.js"></script>
    <script src="p_layout/js/notif.js"></script>
  </body>
  </html>
