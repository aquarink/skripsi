<?php
if (isset($_SESSION['userId'])) {
    // Sessi Ada
    $idStaff = $_SESSION['userId'];
    include_once 'p_controller/database/connection.php';
    $dataUserBySesion = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff'");
    $fetchDataUserBySesion = mysql_fetch_assoc($dataUserBySesion);
    //
    $dokData = mysql_query("SELECT * FROM tb_dokumen WHERE id_dok = '$_GET[verify]'");
    $fetchDokData = mysql_fetch_assoc($dokData);

    if (isset($_GET['read'])) {
        if ($_GET['read'] == true) {
            $changeRead = mysql_query("UPDATE tb_notifikasi SET notif_status = 2 WHERE kode = '$idStaff' AND item = '$_GET[verify]'");
            if ($changeRead) {
                header("Location: ?p=file&verify=$_GET[verify]");
            }
        }
    }


    if (isset($_POST['comment'])) {
        if (!empty($_POST['message'])) {
            // Messages Post
            $messages = mysql_real_escape_string($_POST['message']);
            $saveComment = mysql_query("INSERT INTO tb_message (sender, id_dok, message, mes_create, mes_status) "
                    . "VALUES "
                    . "('$idStaff','$_GET[verify]','$messages',now(),'1')");

            if ($saveComment) {
                // Notif
                $notif = mysql_query("INSERT INTO tb_notifikasi (type, id_staff, kode, item, notif_create, notif_status) "
                        . "VALUES "
                        . "('2','$_SESSION[userId]','$fetchDokData[id_staff]','$_GET[verify]',now(),'1')");
                //
                if ($notif) {
                    header("Refresh: 0");
                } else {
                    echo 'Not F';
                }
            } else {
                echo 'no';
            }
        } else {
            $pesanError = 'Please Write Message';
        }
    }
} else {
    header("Location: ?");
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            <?php
            $text = $fetchDokData['nama_ori'];
            $a = explode(' ', $text);

            $aCount = count($a);

            for ($i = 0; $i < $aCount; $i++) {
                echo ucfirst($a[$i]) . ' ';
            }

            echo ' - ' . $fetchDataUserBySesion['nama'];
            ?>
        </title>

        <link href="p_layout/css/bootstrap.min.css" rel="stylesheet">
        <link href="p_layout/css/navbar-fixed-top.css" rel="stylesheet">
        <link href="p_layout/css/detail.css" rel="stylesheet">
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
                                if(isset($fetchDataUserBySesion['departemen'])) {
                                    if($fetchDataUserBySesion['departemen'] == 2) {
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

        <div class="container" style="padding-top: 20px">
            <div class="row">
                <div class="col-md-6">
                    <?php
                    $shareData = mysql_query("SELECT * FROM tb_share WHERE id_dok = '$_GET[verify]' AND staff_share = '$idStaff'");
                    $verifyShareData = mysql_num_rows($shareData);

                    if ($verifyShareData > 0) {

                        if (isset($_GET['download'])) {
                            if ($_GET['download'] == TRUE) {

                                $file = 'p_file_center/' . $fetchDokData['nama_dok'];
                                if (file_exists($file)) {

                                    $lastCountDownload = $fetchDokData['jml_download'] + 1;
                                    $plusDownload = mysql_query("UPDATE tb_dokumen SET jml_download = '$lastCountDownload' WHERE id_dok = '$_GET[verify]'");
                                    //
                                    if ($plusDownload) {
                                        header('Content-Description: File Transfer');
                                        header('Content-Type: application/octet-stream');
                                        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
                                        header('Expires: 0');
                                        header('Cache-Control: must-revalidate');
                                        header('Pragma: public');
                                        header('Content-Length: ' . filesize($file));
                                        readfile($file);
                                        exit;
                                    } else {
                                        echo 'a';
                                    }
                                }
                            }
                        }
                        ?>
                        <h2><?php echo strtoupper($fetchDokData['nama_ori']) ?></h2>
                        <table class="table">
                            <tr>
                                <td><h4><i class="glyphicon glyphicon-file"></i> Type</h4></td>
                                <td> : </td>
                                <td><?php echo $fetchDokData['jenis_dok'] ?></td>
                            </tr>
                            <tr>
                                <td><h4><i class="glyphicon glyphicon-hdd"></i> Size</h4></td>
                                <td> : </td>
                                <td><?php echo number_format($fetchDokData['ukuran'] / 1000); ?> Kb</td>
                            </tr>
                            <tr>
                                <td><h4><i class="glyphicon glyphicon-download"></i> Download (<?php echo $fetchDokData['jml_download'] ?>x)</h4></td>
                                <td> : </td>
                                <td><a class="btn btn-success" href="?p=file&verify=<?php echo $fetchDokData['id_dok']; ?>&download=true"><i class="glyphicon glyphicon-download"></i> Download File</a></td>
                            </tr>
                        </table>
                    </div>

                    <div class="col-md-6">

                        <?php
                        if ($fetchDokData['jenis_dok'] = 'jpeg' || 'jpg' || 'gif' || 'png') {
                            echo '<img class="img-responsive" src="p_file_center/' . $fetchDokData[nama_dok] . '">';
                        } elseif ($fetchDokData['jenis_dok'] = 'mp4' || 'mp3' || '3gp' || 'mpeg2') {
                            echo '<video class="embed-responsive-item"><source src="p_file_center/' . $fetchDokData[nama_dok] . '" type="video/mp4"></video>';
                        } elseif ($fetchDokData['jenis_dok'] = 'htm' || 'html') {
                            echo '<embed class="embed-responsive-item" src="p_file_center/' . $fetchDokData[nama_dok] . '">';
                        }
                        ?>
                    </div>

                    <?php
                } else {
                    //header("Location: ?p=home");
                }
                ?>
            </div>
        </div>

        <?php
        $comment = mysql_query("SELECT * FROM tb_message WHERE id_dok = '$_GET[verify]' AND mes_status = '1'");
        $countComment = mysql_num_rows($comment);
        ?>

        <div class="container">
            <div class="row">
                <h2><i class="glyphicon glyphicon-comment"></i> Message (<?php echo $countComment; ?>)</h2>
                <div class="qa-message-list" id="wallmessages">
                    <?php while ($fetchComment = mysql_fetch_array($comment)) { ?>
                        <div class="message-item" id="m16">
                            <div class="message-inner">
                                <div class="message-head clearfix">
                                    <div class="user-detail">
                                        <b class="handle">
                                        <?php
                                        $idSender = $fetchComment['sender'];
                                        $sender = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idSender'");
                                        $dataSender = mysql_fetch_assoc($sender);
                                        echo $dataSender['nama'];
                                        ?>
                                        </b>
                                        <small><?php echo date('D d M Y', strtotime($fetchComment['mes_create'])); ?></small>
                                    </div>
                                </div>
                                <div class="qa-message-content">
                                    <?php echo $fetchComment['message']; ?>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </div>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form accept-charset="UTF-8" action="" method="POST">
                            <textarea class="form-control counted" name="message" placeholder="Type in your message" rows="5" style="margin-bottom:10px;"></textarea>
                            <h6 class="pull-right" id="counter">320 characters remaining</h6>
                            <button name="comment" class="btn btn-info" type="submit">Post New Message</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="p_layout/js/jquery.min.js"></script>
    <script src="p_layout/js/bootstrap.min.js"></script>
    <script src="p_layout/js/detail.js"></script>
    <script src="p_layout/js/notif.js"></script>
</body>
</html>
