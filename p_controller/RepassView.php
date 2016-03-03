<?php
if (isset($_SESSION['userId'])) {
    // Sessi Ada
    $idStaff = $_SESSION['userId'];
    include_once 'p_controller/database/connection.php';
    $dataUserBySesion = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff'");
    $fetchDataUserBySesion = mysql_fetch_assoc($dataUserBySesion);

    //oldPassTxt newPassTxt reNewPassTxt

    if (isset($_POST['submit'])) {

        if (empty($_POST['oldPassTxt']) || empty($_POST['newPassTxt']) || empty($_POST['reNewPassTxt'])) {

            $pesanError = 'Please fill the field';
        } else {
            include_once 'p_controller/database/connection.php';
            $oldPassTxt = $_POST['oldPassTxt'];
            $newPassTxt = $_POST['newPassTxt'];
            $reNewPassTxt = $_POST['reNewPassTxt'];
            //
            if ($newPassTxt != $reNewPassTxt) {
                $pesanError = 'Password and Retyping Password not Match';
            } else {
                $cekData = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff' AND password = '$oldPassTxt'");
                $cekAda = mysql_num_rows($cekData);
                if ($cekAda > 0) {
                    // Ada
                    $changePassword = mysql_query("UPDATE tb_staff SET password = '$newPassTxt' WHERE id_staff = '$idStaff'");
                    if ($changePassword) {
                        $pesanError = 'Password Has Changed';
                    } else {
                        $pesanError = 'Failed Change Password';
                    }
                } else {
                    // Tidak
                    $pesanError = 'Wrong Old Password';
                }
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
        <title>Change Password</title>

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

        <script>
            function waktu(id)
            {
                date = new Date;
                year = date.getFullYear();
                month = date.getMonth();
                months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
                d = date.getDate();
                day = date.getDay();
                days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
                h = date.getHours();
                if (h < 10)
                {
                    h = "0" + h;
                }
                m = date.getMinutes();
                if (m < 10)
                {
                    m = "0" + m;
                }
                s = date.getSeconds();
                if (s < 10)
                {
                    s = "0" + s;
                }
                result = '' + days[day] + ' ' + months[month] + ' ' + d + ' ' + year + ' ' + h + ':' + m + ':' + s;
                document.getElementById(id).innerHTML = result;
                setTimeout('waktu("' + id + '");', '1000');
                return true;
            }
        </script>

        <div class="container">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <h2>Change Password</h2>
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Please Fill The Form</h3>
                            <?php
                            if (isset($pesanError)) {
                                echo '<b style=color:red>' . $pesanError . '</b>';
                            }
                            ?>
                        </div>

                        <div class="panel-body">
                            <form role="form" action="" method="POST">

                                <fieldset>

                                    <div class="form-group">
                                        <input class="form-control" name="oldPassTxt" type="password" placeholder="Old Password">
                                    </div>

                                    <div class="form-group">
                                        <input class="form-control" name="newPassTxt" type="password" placeholder="New Password">
                                    </div>

                                    <div class="form-group">
                                        <input class="form-control" name="reNewPassTxt" type="password" placeholder="Re Type New Password">
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-warning" name="submit" type="submit" value="Change Password">
                                    </div>

                                    <span id="waktu"></span>
                                    <script type="text/javascript">window.onload = waktu('waktu');</script>

                                </fieldset>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script src="p_layout/js/jquery.min.js"></script>
        <script src="p_layout/js/bootstrap.min.js"></script>
        <script src="p_layout/js/pebri.js"></script>
        <script src="p_layout/js/notif.js"></script>
    </body>
</html>
