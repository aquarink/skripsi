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
        <title>Notification</title>

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
                        <li class="active"><a href="?p=notif">Notification <b id="datas" style="color:red">0</b></a></li>
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

                <?php
                $dataNotif = mysql_query("SELECT * FROM tb_notifikasi WHERE kode = '$idStaff'");

                while ($fetchUneradNotif = mysql_fetch_array($dataNotif)) {
                    if ($fetchUneradNotif['notif_status'] == 1) {
                        if ($fetchUneradNotif['type'] == 1) {
                            ?>
                            <a href="?p=file&verify=<?php echo $fetchUneradNotif['item']; ?>&read=true" class="btn btn-info" style="width: 100%; text-align: left; color: #000; font-weight: bold">
                                <i class="glyphicon glyphicon-eye-open"></i>
                                <?php
                                $userData = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$fetchUneradNotif[id_staff]'");
                                $fetchDataUser = mysql_fetch_assoc($userData);

                                $dokData = mysql_query("SELECT * FROM tb_dokumen WHERE id_dok = '$fetchUneradNotif[item]'");
                                $fetchDokData = mysql_fetch_assoc($dokData);

                                echo '<span style="color:#fff">' . $fetchDataUser['nama'] . '</span> Shared New File <span style="color:#fff">' . $fetchDokData['nama_ori'] . '</span> With You';
                                ?>
                            </a>
                            <hr>
                            <?php
                        }
                        if ($fetchUneradNotif['type'] == 2) {
                            ?>
                            <a href="?p=file&verify=<?php echo $fetchUneradNotif['item']; ?>&read=true" class="btn btn-info" style="width: 100%; text-align: left; color: #000; font-weight: bold">
                                <i class="glyphicon glyphicon-eye-open"></i>
                                <?php
                                $userData = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$fetchUneradNotif[id_staff]'");
                                $fetchDataUser = mysql_fetch_assoc($userData);

                                $dokData = mysql_query("SELECT * FROM tb_dokumen WHERE id_dok = '$fetchUneradNotif[item]'");
                                $fetchDokData = mysql_fetch_assoc($dokData);

                                echo '<span style="color:#fff">' . $fetchDataUser['nama'] . '</span> Send New Message On File <span style="color:#fff">' . $fetchDokData['nama_ori'] . '</span> For You';
                                ?>
                            </a>
                            <hr>
                            <?php
                        }
                        ?>


                        <?php
                    } elseif ($fetchUneradNotif['notif_status'] == 2) {
                        if ($fetchUneradNotif['type'] == 1) {
                            ?>
                            <a href="?p=file&verify=<?php echo $fetchUneradNotif['item']; ?>&read=true" class="btn btn-default" style="width: 100%; text-align: left; color: #000; font-weight: bold">
                                <i class="glyphicon glyphicon-eye-open"></i>
                                <?php
                                $userData = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$fetchUneradNotif[id_staff]'");
                                $fetchDataUser = mysql_fetch_assoc($userData);

                                $dokData = mysql_query("SELECT * FROM tb_dokumen WHERE id_dok = '$fetchUneradNotif[item]'");
                                $fetchDokData = mysql_fetch_assoc($dokData);

                                echo '<span style="color:#00ABD2">' . $fetchDataUser['nama'] . '</span> Shared File <span style="color:#00ABD2">' . $fetchDokData['nama_ori'] . '</span> With You';
                                ?>
                            </a>
                            <hr>
                            <?php
                        }
                        if ($fetchUneradNotif['type'] == 2) {
                            ?>
                            <a href="?p=file&verify=<?php echo $fetchUneradNotif['item']; ?>&read=true" class="btn btn-default" style="width: 100%; text-align: left; color: #000; font-weight: bold">
                                <i class="glyphicon glyphicon-eye-open"></i>
                                <?php
                                $userData = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$fetchUneradNotif[id_staff]'");
                                $fetchDataUser = mysql_fetch_assoc($userData);

                                $dokData = mysql_query("SELECT * FROM tb_dokumen WHERE id_dok = '$fetchUneradNotif[item]'");
                                $fetchDokData = mysql_fetch_assoc($dokData);

                                echo '<span style="color:#00ABD2">' . $fetchDataUser['nama'] . '</span> Send Message On File <span style="color:#00ABD2">' . $fetchDokData['nama_ori'] . '</span> For You';
                                ?>
                            </a>
                            <hr>
                            <?php
                        }
                    }
                }
                ?>




            </div>

        </div>
        <script src="p_layout/js/jquery.min.js"></script>
        <script src="p_layout/js/bootstrap.min.js"></script>
        <script src="p_layout/js/notif.js"></script>
    </body>
</html>
