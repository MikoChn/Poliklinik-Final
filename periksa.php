<?php
include_once("koneksi.php");
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Jika belum, alihkan ke halaman login
    header("Location: index.php?page=loginUser");
    exit;
}
?>

<style>
    .tags {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }
    .tag {
        background-color: #e2e3e5;
        border-radius: 20px;
        padding: 5px 10px;
        display: flex;
        align-items: center;
    }
    .tag button.remove-tag {
        background: none;
        border: none;
        margin-left: 5px;
        cursor: pointer;
    }
</style>

<div class="form-group mx-sm-3 mb-2">
    <form action="" onsubmit="return(validate());" method="post">
        <?php
        $id = '';
        $id_pasien = '';
        $id_dokter = '';
        $tgl_periksa = '';
        $catatan = '';
        if (isset($_GET['id'])) {
            $ambil = mysqli_query($mysqli, "SELECT * FROM periksa WHERE id='" . $_GET['id'] . "'");
            while ($row = mysqli_fetch_array($ambil)) {
                $id = $row['id'];
                $id_pasien = $row['id_pasien'];
                $id_dokter = $row['id_dokter'];
                $tgl_periksa = $row['tgl_periksa'];
                $catatan = $row['catatan'];
            }
            ?>
            <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php
        }
        ?>
        <!-- SELECT PASIEN -->
        <label class="fw-bold">Pasien</label>
        <select class="form-control my-2" name="id_pasien" id="id_pasien">
            <?php
            $selected = '';
            $periksa = mysqli_query($mysqli, "SELECT * FROM pasien");
            while ($data = mysqli_fetch_array($periksa)) {
                if ($data['id'] == $id_pasien) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                ?>
                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                <?php
            }
            ?>
        </select>
        
        <!-- ALERGI PASIEN -->
        <label class="fw-bold">Alergi</label>
        <input type="text" class="form-control my-2" id="alergi_pasien" readonly>
        
        
        <!-- SELECT DOKTER -->
        <label class="fw-bold">Dokter</label>
        <select class="form-control my-2" name="id_dokter">
            <?php
            $selected = '';
            $dokter = mysqli_query($mysqli, "SELECT * FROM dokter");
            while ($data = mysqli_fetch_array($dokter)) {
                if ($data['id'] == $id_dokter) {
                    $selected = 'selected="selected"';
                } else {
                    $selected = '';
                }
                ?>
                <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                <?php
            }
            ?>
        </select>
        
        <!-- INPUT TANGGAL DAN CATATAN -->
        <label class="fw-bold">Tanggal Periksa</label>
        <input type="datetime-local" name="tgl_periksa" value="<?php echo $tgl_periksa ?>" class="form-control my-2" required>
        
        <label class="fw-bold">Catatan</label>
        <input type="text" class="form-control my-2" name="catatan" value="<?php echo $catatan ?>"> 
      <!-- MULTIPLE CHOICE FOR OBAT -->
        <div class="mb-3">
        <label class="form-label fw-bold">Obat</label>
        <select id="obat-select" class="form-control" multiple>
            <?php
            $obatResult = mysqli_query($mysqli, "SELECT * FROM obat");
            while ($obatData = mysqli_fetch_array($obatResult)) {
                ?>
                <option value="<?php echo $obatData['id']; ?>"><?php echo $obatData['nama_obat']; ?></option>
                <?php
            }
            ?>
         </select>
    <small class="text-muted">Pastikan obat yang dipilih tidak mengandung bahan yang dapat menyebabkan alergi pada pasien.</small>
    </div>

        <input type="hidden" name="obat" id="obat-hidden-input">
        <div id="obat-tags" class="tags"></div>
        <button class="btn btn-primary" type="submit" name="simpan" >Submit</button>
    </form>
</div>

<!-- TABLE -->
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Pasien</th>
                <th scope="col">Dokter</th>
                <th scope="col">Tanggal Periksa</th>
                <th scope="col">Catatan</th>
                <th scope="col">Obat</th>
                <th scope="col">Alergi</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            date_default_timezone_set("Asia/Jakarta");
            $result = mysqli_query($mysqli, "SELECT pr.*, d.nama AS 'nama_dokter', p.nama AS 'nama_pasien', p.alergi AS 'alergi_pasien' FROM periksa pr LEFT JOIN dokter d ON (pr.id_dokter=d.id) LEFT JOIN pasien p ON (pr.id_pasien=p.id) ORDER BY pr.id ASC");
            while ($data = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?php echo $data['id'] ?></td>
                    <td><?php echo $data['nama_pasien'] ?></td>
                    <td><?php echo $data['nama_dokter'] ?></td>
                    <td><?php echo date('d-M-Y H:i:s', strtotime($data['tgl_periksa'])) ?></td>
                    <td><?php echo $data['catatan'] ?></td>
                    <td>
                        <?php
                        // Menampilkan obat yang terkait
                        $selectedObat = mysqli_query($mysqli, "SELECT o.nama_obat FROM detail_periksa dp JOIN obat o ON dp.id_obat = o.id WHERE dp.id_periksa = '" . $data['id'] . "'");
                        while ($obatData = mysqli_fetch_array($selectedObat)) {
                            echo $obatData['nama_obat'] . "<br>";
                        }
                        ?>
                    </td>
                    <td><?php echo $data['alergi_pasien'] ?></td>
                    <td>
                        <a class="btn btn-success rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>">Ubah</a>
                        <a class="btn btn-danger rounded-pill px-3" href="index.php?page=periksa&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                        <a class="btn btn-primary rounded-pill px-3" href="index.php?page=invoice&id=<?php echo $data['id'] ?>">Nota</a>
                    </td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>


<?php
if (isset($_POST['simpan'])) {
    $id_pasien = $_POST['id_pasien'];
    $id_dokter = $_POST['id_dokter'];
    $tgl_periksa = $_POST['tgl_periksa'];
    $catatan = $_POST['catatan'];
    $obat = explode(',', $_POST['obat']);
    
    if (isset($_POST['id'])) {
        $id_periksa = $_POST['id'];
        $sql = "UPDATE periksa SET id_pasien='$id_pasien', id_dokter='$id_dokter', tgl_periksa='$tgl_periksa', catatan='$catatan' WHERE id='$id_periksa'";
        if ($mysqli->query($sql) === TRUE) {
            // Clear previous medication details
            $mysqli->query("DELETE FROM detail_periksa WHERE id_periksa='$id_periksa'");
            // Insert new medication details
            foreach ($obat as $id_obat) {
                $id_obat = intval($id_obat); // Ensure the ID is an integer
                if ($id_obat > 0) { // Check for valid ID
                    $insert_sql = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES ('$id_periksa', '$id_obat')";
                    $mysqli->query($insert_sql);
                }
            }
            echo "<script>location='index.php?page=periksa';</script>";
        } else {
            echo "Error updating record: " . $mysqli->error;
        }
    } else {
        $sql = "INSERT INTO periksa (id_pasien, id_dokter, tgl_periksa, catatan) VALUES ('$id_pasien', '$id_dokter', '$tgl_periksa', '$catatan')";
        if ($mysqli->query($sql) === TRUE) {
            $id_periksa = $mysqli->insert_id;
            // Insert new medication details
            foreach ($obat as $id_obat) {
                $id_obat = intval($id_obat); // Ensure the ID is an integer
                if ($id_obat > 0) { // Check for valid ID
                    $insert_sql = "INSERT INTO detail_periksa (id_periksa, id_obat) VALUES ('$id_periksa', '$id_obat')";
                    $mysqli->query($insert_sql);
                }
            }
            echo "<script>location='index.php?page=periksa';</script>";
        } else {
            echo "Error inserting record: " . $mysqli->error;
        }
    }
}

if (isset($_GET['id']) && isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id_periksa = $_GET['id'];
    // Delete associated records in detail_periksa
    $sql_detail = "DELETE FROM detail_periksa WHERE id_periksa='$id_periksa'";
    if ($mysqli->query($sql_detail) === TRUE) {
        // Now delete the record in periksa
        $sql = "DELETE FROM periksa WHERE id='$id_periksa'";
        if ($mysqli->query($sql) === TRUE) {
            echo "<script>location='index.php?page=periksa';</script>";
        } else {
            echo "Error deleting record: " . $mysqli->error;
        }
    } else {
        echo "Error deleting detail records: " . $mysqli->error;
    }
}
?>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const select = document.getElementById('obat-select');
        const hiddenInput = document.getElementById('obat-hidden-input');
        const tagsContainer = document.getElementById('obat-tags');
        const idPasienSelect = document.getElementById('id_pasien');
        const alergiPasienInput = document.getElementById('alergi_pasien');

        function updateTags() {
            const selectedOptions = Array.from(select.selectedOptions);
            hiddenInput.value = selectedOptions.map(option => option.value).join(',');
            tagsContainer.innerHTML = selectedOptions.map(option => 
                `<span class="tag">${option.text}<button type="button" class="remove-tag" data-value="${option.value}">&times;</button></span>`
            ).join('');
        }

        select.addEventListener('change', updateTags);

        tagsContainer.addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-tag')) {
                const valueToRemove = event.target.getAttribute('data-value');
                const optionToRemove = Array.from(select.options).find(option => option.value === valueToRemove);
                optionToRemove.selected = false;
                updateTags();
            }
        });

        // Fetch alergi when pasien is selected
        idPasienSelect.addEventListener('change', function() {
            const idPasien = this.value;
            if (idPasien) {
                fetch(`fetch_alergi.php?id_pasien=${idPasien}`)
                    .then(response => response.json())
                    .then(data => {
                        alergiPasienInput.value = data.alergi;
                    });
            } else {
                alergiPasienInput.value = '';
            }
        });

        updateTags();
    });
</script>
