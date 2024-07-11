<?php
include_once("koneksi.php");

// Cek jika user belum login, maka alihkan ke halaman login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php?page=loginUser");
    exit;
}

// Proses simpan data obat
if (isset($_POST['simpan'])) {
    $nama_obat = $_POST['nama_obat'];
    $kemasan = $_POST['kemasan'];
    $harga = $_POST['harga'];
    $kandungan_obat = $_POST['kandungan_obat'];

    if (isset($_POST['id'])) {
        // Jika ada id, lakukan update data obat
        $id = $_POST['id'];
        $query = "UPDATE obat SET 
                    nama_obat = '$nama_obat', 
                    kemasan = '$kemasan', 
                    harga = '$harga',
                    kandungan_obat = '$kandungan_obat'
                  WHERE id = $id";
    } else {
        // Jika tidak ada id, lakukan penambahan data obat baru
        $query = "INSERT INTO obat (nama_obat, kemasan, harga, kandungan_obat) 
                  VALUES ('$nama_obat', '$kemasan', '$harga', '$kandungan_obat')";
    }

    if ($mysqli->query($query) === TRUE) {
        echo "<script>alert('Data obat berhasil disimpan');</script>";
        echo "<script>window.location='index.php?page=obat';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $mysqli->error;
    }
}

// Proses hapus data obat
if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM obat WHERE id = $id";

    if ($mysqli->query($query) === TRUE) {
        echo "<script>alert('Data obat berhasil dihapus');</script>";
        echo "<script>window.location='index.php?page=obat';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $mysqli->error;
    }
}

// Ambil data obat untuk proses edit
$nama_obat = '';
$kemasan = '';
$harga = '';
$kandungan_obat = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM obat WHERE id = $id";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nama_obat = $row['nama_obat'];
        $kemasan = $row['kemasan'];
        $harga = $row['harga'];
        $kandungan_obat = $row['kandungan_obat'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Obat</title>
    <!-- Bootstrap offline -->
    <link rel="stylesheet" href="assets/css/bootstrap.css"> 
    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <form class="row g-3" method="POST" action="" name="myForm" onsubmit="return(validate());">
            <?php if (isset($_GET['id'])): ?>
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php endif; ?>
            <div class="col-md-6">
                <label for="nama_obat" class="form-label fw-bold">Nama Obat</label>
                <input type="text" class="form-control" id="nama_obat" name="nama_obat" value="<?php echo $nama_obat ?>" required>
            </div>
            <div class="col-md-6">
                <label for="kemasan" class="form-label fw-bold">Kemasan</label>
                <input type="text" class="form-control" id="kemasan" name="kemasan" value="<?php echo $kemasan ?>" required>
            </div>
            <div class="col-md-6">
                <label for="harga" class="form-label fw-bold">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="<?php echo $harga ?>" required>
            </div>
            <div class="col-md-6">
                <label for="kandungan_obat" class="form-label fw-bold">Kandungan Obat</label>
                <textarea class="form-control" id="kandungan_obat" name="kandungan_obat" rows="3"><?php echo $kandungan_obat ?></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary rounded-pill px-4" name="simpan">Simpan</button>
            </div>
        </form>

        <!-- Table-->
        <div class="table-responsive mt-4">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">No</th>
                        <th scope="col">Nama Obat</th>
                        <th scope="col">Kemasan</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Kandungan Obat</th>
                        <th scope="col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM obat ORDER BY nama_obat DESC";
                    $result = $mysqli->query($query);
                    $no = 1;
                    while ($data = $result->fetch_assoc()) {
                        ?>
                        <tr>
                            <th scope="row"><?php echo $no++ ?></th>
                            <td><?php echo $data['nama_obat'] ?></td>
                            <td><?php echo $data['kemasan'] ?></td>
                            <td><?php echo $data['harga'] ?></td>
                            <td><?php echo $data['kandungan_obat'] ?></td>
                            <td>
                                <a class="btn btn-success rounded-pill px-3" href="index.php?page=obat&id=<?php echo $data['id'] ?>">Ubah</a>
                                <a class="btn btn-danger rounded-pill px-3" href="index.php?page=obat&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
$mysqli->close(); // Tutup koneksi database
?>
