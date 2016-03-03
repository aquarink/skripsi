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
        <title>Welcome Home</title>

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
                        <li class="active"><a href="?p=home">Shared File</a></li>
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
                        <h1>Shared File</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-lg-offset-4">
                    <input type="search" id="search" value="" class="form-control" placeholder="Search File">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <form action="" method="POST">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>From</th>
                                    <th>Kind File</th>
                                    <th>Created</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $shareData = mysql_query("SELECT * FROM tb_share WHERE staff_share = '$idStaff'");
                                while ($fetchShareData = mysql_fetch_array($shareData)) {
                                    if ($fetchShareData['dok_owner'] != $idStaff) {
                                        //
                                        $dokData = mysql_query("SELECT * FROM tb_dokumen WHERE id_dok = '$fetchShareData[id_dok]'");
                                        $fetchDokData = mysql_fetch_assoc($dokData);
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                $text = $fetchDokData['nama_ori'];
                                                $a = explode(' ', $text);

                                                $aCount = count($a);

                                                for ($i = 0; $i < $aCount; $i++) {
                                                    echo ucfirst($a[$i]) . ' ';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $staffData = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$fetchShareData[dok_owner]'");
                                                $fetchStaffData = mysql_fetch_assoc($staffData);
                                                echo $fetchStaffData['nama'];
                                                ?>
                                            </td>
                                            <td><?php echo $fetchDokData['jenis_dok']; ?></td>
                                            <td><?php echo date('D d M Y', strtotime($fetchDokData['dok_create'])); ?></td>
                                            <td>
                                                <a href="?p=file&verify=<?php echo $fetchDokData['id_dok']; ?>" style="width:45%" class="btn btn-info"><i class="glyphicon glyphicon-info-sign"></i> Detail</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
            <hr>
        </div>

        <script src="p_layout/js/jquery.min.js"></script>
        <script src="p_layout/js/bootstrap.min.js"></script>
        <script src="p_layout/js/pebri.js"></script>
        <script src="p_layout/js/notif.js"></script>
    </body>
</html>
