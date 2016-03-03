<?php
if (isset($_SESSION['userId'])) {
    // Sessi Ada

    if (isset($_GET['verify'])) {
        include_once 'p_controller/database/connection.php';
        $dokOwner = mysql_query("SELECT * FROM tb_dokumen WHERE id_dok = '$_GET[verify]' AND id_staff = '$_SESSION[userId]'");
        $cekDokOwner = mysql_num_rows($dokOwner);
        if ($cekDokOwner > 0) {
            $idStaff = $_SESSION['userId'];

            $dataUserBySesion = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff'");
            $fetchDataUserBySesion = mysql_fetch_assoc($dataUserBySesion);

            //
            $status = $_POST['status'];
            $id_staff = $_POST['id_staff'];
            if (isset($_POST['submit'])) {
                $i = 0;
                for ($i = 0; $i < count($status); $i++) {
                    if ($_POST['u' . $i] == null) {
                        $u = 0;
                    } else {
                        $u = 1;
                    }

                    if ($u != $status[$i]) {
                        if ($u == 0) {
                            $saveShare = mysql_query("DELETE FROM tb_share WHERE staff_share = '" . $id_staff[$i] . "'");
                            //echo "id " . $id_staff[$i] . " sudah di hapus di tbl share <br>";
                        }
                        if ($u == 1) {

                            //Check Data Share
                            $dataShare = mysql_query("SELECT * FROM tb_share WHERE id_dok = '$_GET[verify]' AND dok_owner = '$_SESSION[userId]'");
                            $verifyDataShare = mysql_num_rows($dataShare);

                            // Chek
                            if ($verifyDataShare > 0) {
                                echo 'Sudah Pernah Di Share';
                            } else {
                                $saveShare = mysql_query("INSERT INTO tb_share (id_dok, dok_owner, staff_share, share_create, share_status) "
                                        . "VALUES('$_GET[verify]','$_SESSION[userId]','" . $id_staff[$i] . "',now(),'1')");
                                //echo "id " . $id_staff[$i] . " berhasil di tambah di tbl share <br>";
                                //
                                
                                if ($saveShare) {
                                    $changeDokStat = mysql_query("UPDATE tb_dokumen SET dok_status = '2' WHERE id_dok = '$_GET[verify]'");

                                    if ($changeDokStat) {
                                        // Notif
                                        $notif = mysql_query("INSERT INTO tb_notifikasi (type, id_staff, kode, item, notif_create, notif_status) "
                                                . "VALUES "
                                                . "('1','$_SESSION[userId]','$id_staff[$i]','$_GET[verify]',now(),'1')");
                                        //
                                    } else {
                                        echo 'No ' . $changeDokStat;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            header("Location: ?p=home");
        }
    } else {
        header("Location: ?p=home");
    }
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
                                if (isset($fetchDataUserBySesion['staff_status'])) {
                                    if ($fetchDataUserBySesion['staff_status'] == 2) {
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
                        <h1>Share File With :</h1>
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
                    <form action="" method="POST">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkall" /> <small>Share All</small></th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Potition</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $id_dok = $_GET['verify'];
                                $dataStaff = mysql_query("SELECT * FROM tb_staff AS st LEFT JOIN tb_share AS sh ON st.`id_staff` = sh.`staff_share` WHERE st.`staff_status`= '1' ORDER BY st.`nama` ASC");
                                $i = 0;
                                while ($user = mysql_fetch_array($dataStaff)) {
                                    if ($user['id_staff'] != $idStaff) {
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                if ($user['id_dok'] == $id_dok) {
                                                    echo "<input type='checkbox' class='checkthis' name='u$i' value='$user[id_staff];' checked> Already Share";
                                                    echo "<input name='status[]' type='hidden' value='1'>";
                                                } else {
                                                    echo "<input type='checkbox' class='checkthis' name='u$i' value='$user[id_staff];' >";
                                                    echo "<input name='status[]' type='hidden' value='0'>";
                                                }
                                                ?>

                                                <small></small>
                                            </td>
                                            <input name='id_staff[]' type='hidden' value='<?php echo $user['id_staff'] ?>'>
                                            <td><?php echo $user['nama'] ?></td>
                                            <td><?php echo $user['departemen'] ?></td>
                                            <td><?php echo $user['jabatan'] ?></td>
                                        </tr>
                                        <?php
                                        $i++;
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                        <hr>
                        <button id="off" onclick="uploadFile()" name="submit" type="submit" href="#" class="btn btn-primary">
                            <i class="glyphicon glyphicon-send"></i> Send
                        </button>
                    </form>
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
