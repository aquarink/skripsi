<?php
if (isset($_SESSION['userId'])) {
  // Sessi Ada
  $idStaff = $_SESSION['userId'];
  include_once 'p_controller/database/connection.php';
  $dataUserBySesion = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff'");
  $fetchDataUserBySesion = mysql_fetch_assoc($dataUserBySesion);

  if(isset($_GET['verify'])) {
    // Logic Upload
    if(isset($_POST['add'])) {
      if($_POST['dept'] == 1) {

        if(empty($_POST['nik']) || empty($_POST['nama']) || empty($_POST['posisi'])) {
          echo $error = 'Form Harus Diisi..!';
        } else {
          // New
          $lastIdDep = mysql_query("SELECT * FROM tb_departemen ORDER BY id_dep DESC LIMIT 1");
          $fetchDataDeptLimit = mysql_fetch_assoc($lastIdDep);
          $newId = $fetchDataDeptLimit['id_dep']+1;
          //
          $id = $_GET['verify'];
          $nik = $_POST['nik'];
          $nama = $_POST['nama'];
          $posisi = $_POST['posisi'];
          $newDept = $_POST['newDept'];
          $status = $_POST['statusKerja'];
          //
          $saveNewDept = mysql_query("INSERT INTO tb_departemen VALUES ('','$newDept')");

          if($saveNewDept) {
            $saveNewStaff = mysql_query("UPDATE tb_staff SET nik = '$nik', nama = '$nama', jabatan = '$posisi', staff_status = '$status', departemen = '$newId' WHERE id_staff = '$id'");
            if($saveNewStaff) {
              header("Location: ?p=staff&e=Update Berhasil");
            } else {
              header("Location: ?p=detailstaff&staff=$_GET[verify]&e=Update Gagal1");
            }
          } else {
            echo "stringssss";
          }

        }

      } elseif ($_POST['dept'] == 2) {

        if(empty($_POST['nik']) || empty($_POST['nama']) || empty($_POST['posisi']) || empty($_POST['oldDept'])) {
          echo $error = 'Form Harus Diisi';
        } else {
          // Old
          $id = $_GET['verify'];
          $nik = $_POST['nik'];
          $nama = $_POST['nama'];
          $posisi = $_POST['posisi'];
          $oldDept = $_POST['oldDept'];
          $status = $_POST['statusKerja'];
          //
          $saveNewStaff = mysql_query("UPDATE tb_staff SET nik = '$nik', nama = '$nama', jabatan = '$posisi', staff_status = '$status', departemen = '$oldDept' WHERE id_staff = '$id'");
          if($saveNewStaff) {
            header("Location: ?p=staff&e=Updates Berhasil");
          } else {
            //header("Location: ?p=detailstaff&staff=$_GET[verify]&e=Update Gagal2");
          }
        }

      } else {
        echo $error = 'Chose Status of Departemen, New Or Exists';
      }
    }
  } else {
    // -------------------------------------------------------- //
    // Logic Upload
    if(isset($_POST['add'])) {
      if($_POST['dept'] == 1) {

        if(empty($_POST['nik']) || empty($_POST['nama']) || empty($_POST['posisi']) || empty($_POST['newDept'])) {
          echo $error = 'Form Harus Diisi';
        } else {
          // New
          $lastIdDep = mysql_query("SELECT * FROM tb_departemen ORDER BY id_dep DESC LIMIT 1");
          $fetchDataDeptLimit = mysql_fetch_assoc($lastIdDep);
          $newId = $fetchDataDeptLimit['id_dep']+1;
          //
          $nik = $_POST['nik'];
          $nama = $_POST['nama'];
          $posisi = $_POST['posisi'];
          $newDept = $_POST['newDept'];
          //
          $saveNewDept = mysql_query("INSERT INTO tb_departemen VALUES ('','$newDept')");

          if($saveNewDept) {
            $saveNewStaff = mysql_query("INSERT INTO tb_staff VALUES('','$nik','$nama','password','$posisi',now(),1,'$newId')");
            if($saveNewStaff) {
              header("Location: ?p=staff&e=Berhasil");
            } else {
              header("Location: ?p=addstaff&e=Gagal");
            }
          }

        }

      } elseif ($_POST['dept'] == 2) {

        if(empty($_POST['nik']) || empty($_POST['nama']) || empty($_POST['posisi']) || empty($_POST['oldDept'])) {
          echo $error = 'Form Harus Diisi';
        } else {
          // Old
          $nik = $_POST['nik'];
          $nama = $_POST['nama'];
          $posisi = $_POST['posisi'];
          $oldDept = $_POST['oldDept'];
          //
          $saveNewStaff = mysql_query("INSERT INTO tb_staff (nik, nama, PASSWORD, jabatan, staff_create, staff_status, departemen) VALUES('$nik','$nama','password','$posisi',now(),'1','$oldDept')");
          if($saveNewStaff) {
            header("Location: ?p=staff&e=Berhasil");
          } else {
            header("Location: ?p=addstaff&e=Gagal");
          }
        }

      } else {
        echo $error = 'Chose Status of Departemen, New Or Exists';
      }
    }
  }
} else {
  header("Location: ?");
}
?>
