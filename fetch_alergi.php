<?php
include_once("koneksi.php");

if (isset($_GET['id_pasien'])) {
    $id_pasien = $_GET['id_pasien'];
    $query = "SELECT alergi FROM pasien WHERE id='$id_pasien'";
    $result = mysqli_query($mysqli, $query);

    if ($result) {
        $data = mysqli_fetch_assoc($result);
        echo json_encode($data);
    } else {
        echo json_encode(['alergi' => '']);
    }
} else {
    echo json_encode(['alergi' => '']);
}
?>
