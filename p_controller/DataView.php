<?php

if (isset($_SESSION['userId'])) {
    // Sessi Ada
    $idStaff = $_SESSION['userId'];
    include_once 'p_controller/database/connection.php';
    $dataUserBySesion = mysql_query("SELECT * FROM tb_staff WHERE id_staff = '$idStaff'");
    $fetchDataUserBySesion = mysql_fetch_assoc($dataUserBySesion);

    // Data Notif
    if (isset($_GET['id'])) {
        $notifData = mysql_query("SELECT * FROM tb_notifikasi WHERE kode = '$idStaff' AND notif_status = '1'");

        $count = mysql_num_rows($notifData);
        if ($count == 0) {
            
        } else {
            echo $count;
        }
    }
} else {
    header("Location: ?");
}
?>