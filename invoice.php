<?php
include_once("koneksi.php");

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php?page=loginUser");
    exit;
}

$id_periksa = isset($_GET['id']) ? $_GET['id'] : '';

// Fetch the periksa data along with related patient and doctor information
$periksaData = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT pr.*, p.nama as 'nama_pasien', p.alamat as 'alamat_pasien', p.no_hp as 'no_hp_pasien', d.nama as 'nama_dokter', d.alamat as 'alamat_dokter', d.no_hp as 'no_hp_dokter' FROM periksa pr LEFT JOIN pasien p ON pr.id_pasien = p.id LEFT JOIN dokter d ON pr.id_dokter = d.id WHERE pr.id = '$id_periksa'"));

// Fetch the medications related to the periksa
$obatData = mysqli_query($mysqli, "SELECT o.nama_obat, o.harga FROM detail_periksa dp JOIN obat o ON dp.id_obat = o.id WHERE dp.id_periksa = '$id_periksa'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        .invoice-box {
            max-width: 900px;
            height: 100%;
            margin: 40px auto;
            padding: 40px;
            border: 1px solid #eee;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            font-size: 18px;
            line-height: 28px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 10px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <h2>Nota Pembayaran</h2>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                No Periksa <br> <span> <b> <?php echo $periksaData['id']; ?><br>
                            </td>
                            <td>
                                Tanggal Periksa <br> <span> <b> <?php echo date('Y-m-d H:i:s', strtotime($periksaData['tgl_periksa'])); ?><br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                <b> Pasien </b><br><?php echo $periksaData['nama_pasien']; ?><br>
                                Alamat: <?php echo $periksaData['alamat_pasien']; ?><br>
                                No. HP: <?php echo $periksaData['no_hp_pasien']; ?>
                            </td>
                            <td>
                                <b> Dokter </b><br><?php echo $periksaData['nama_dokter']; ?><br>
                                Alamat: <?php echo $periksaData['alamat_dokter']; ?><br>
                                No. HP: <?php echo $periksaData['no_hp_dokter']; ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Deskripsi</td>
                <td>Harga</td>
            </tr>
            <tr class="item">
                <td>Jasa Dokter</td>
                <td>Rp 150.000,00</td>
            </tr>
            <?php
            $totalObat = 0;
            while ($row = mysqli_fetch_assoc($obatData)) {
                $totalObat += $row['harga'];
                echo "<tr class='item'><td>{$row['nama_obat']}</td><td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td></tr>";
            }
            ?>
            <tr class="total">
                <td></td>
                <td>Jasa Dokter: Rp 150.000</td>
            </tr>
            <tr class="total">
                <td></td>
                <td>Subtotal Obat: Rp <?php echo number_format($totalObat, 0, ',', '.'); ?></td>
            </tr>
            <tr class="total">
                <td></td>
                <td>Total: <span style="color: green;">Rp <?php echo number_format(150000 + $totalObat, 0, ',', '.'); ?></span></td>
            </tr>
        </table>
    </div>
</body>
</html>
