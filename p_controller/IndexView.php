<?php
if (isset($_SESSION['userId'])) {
    // Seesi Ada
    header("Location: ?p=uploadfile");
} else {
    // Sessi Tidak Ada
    if (isset($_POST['submit'])) {

        if (empty($_POST['nikTxt']) || empty($_POST['passTxt'])) {

            $pesanError = 'Please fill the field';
        } else {
            include_once 'p_controller/database/connection.php';
            $nik = $_POST['nikTxt'];
            $pas = $_POST['passTxt'];
            $cekData = mysql_query("SELECT * FROM tb_staff WHERE nik = '$nik' AND password = '$pas'");
            $cekAda = mysql_num_rows($cekData);
            if ($cekAda > 0) {
                //
                $dataUser = mysql_fetch_array($cekData);
                // Set Sessi
                $_SESSION['userId'] = $dataUser['id_staff'];
                header("Refresh: 0");
            } else {
                $pesanError = 'Nik and Password not Match';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <title>Welcome Please Sign In</title>

        <link href="p_layout/css/bootstrap.min.css" rel="stylesheet">
        <script src="p_layout/js/bootstrap.min.js"></script>
        <script src="p_layout/js/jquery.min.js"></script>

    </head>

    <body>

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
                    <h2>JNY ARCHIVE SYSTEM</h2>
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Please Sign In</h3>
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
                                        <input class="form-control" name="nikTxt" type="number" autofocus="" placeholder="NIK" value="">
                                    </div>

                                    <div class="form-group">
                                        <input class="form-control" name="passTxt" type="password" placeholder="Password">
                                    </div>

                                    <div class="form-group">
                                        <input class="btn btn-success" name="submit" type="submit" value="Sign In">
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

    </body>
</html>