<?php
if (isset($_SESSION['userId'])) {
  // Sessi Ada
  $idStaff = $_SESSION['userId'];
  include_once 'p_controller/database/connection.php';
  $dataUserBySesion = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff'");
  $fetchDataUserBySesion = mysql_fetch_assoc($dataUserBySesion);

  // Logic Upload
  if (isset($_POST['submit'])) {
    if (empty($_POST['titleTxt']) || empty($_FILES['fileTxt']['tmp_name'])) {
      $pesanError = 'Please fill the field and pick file, <a href="?p=uploadfile">Back</a>';
    } else {
      //print_r($_FILES['fileTxt']);
      $judul = $_POST['titleTxt'];
      $files = $_FILES['fileTxt'];
      $fileCode = rand(1111, 9999);
      $oriTitle = $_POST['titleTxt'];
      $title = strtolower(str_replace(' ', '-', $oriTitle));
      //
      $name = $files['name'];
      $location = $files['tmp_name'];
      $size = $files['size'];
      //
      $fileSub = substr($files['name'], -5);
      $tipeDok = explode('.', $fileSub);
      if (isset($tipeDok[1])) {
        $type = $tipeDok[1];
        //
        $dest = $fileCode . '-' . $title . '.' . $type;
        $moveFiles = move_uploaded_file($location, "p_file_center/" . $dest);

        if ($moveFiles) {
          $saveDataFile = mysql_query("INSERT INTO tb_dokumen "
          . "(id_staff, nama_dok, nama_ori, jenis_dok, ukuran, jml_download, dok_create, dok_status) "
          . "VALUES "
          . "('$idStaff','$dest','$judul','$type','$size','0',now(),'1')");
          if ($saveDataFile) {

            $lastDokRecord = mysql_query("SELECT * FROM tb_dokumen WHERE id_staff = '$idStaff' ORDER BY id_dok DESC LIMIT 1");
            $lastId = mysql_fetch_assoc($lastDokRecord);
            header("Location: ?p=share&verify=$lastId[id_dok]");
          } else {
            echo 'Save Dokumen Gagal';
          }
        } else {
          echo 'Move Dokumen Gagal';
        }
      } else {
        header("location: ?");
      }
    }
  }
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
</script>
</head>

<body>

  <div class="container">

    <center><img src="p_layout/images/loading1.gif"></center>

    <?php
    if (isset($pesanError)) {
      echo '<center><b style="color:red">' . $pesanError . '</b></center>';
    } else {
      echo '<center><b style="color:green">Please wait, file still uploading,</b><a href="?p=uploadfile"> Cancel</a></center>';
    }
    ?>
  </div>
</body>
</html>
