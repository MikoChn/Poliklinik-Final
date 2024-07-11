<!DOCTYPE html>
<?php
// Mulai sesi
session_start();
// Cek status login pengguna
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Jika pengguna sudah login, tampilkan tombol logout
    $navbar_text = 'Logout';
    $navbar_link = 'logout.php'; // Ganti logout.php dengan skrip logout Anda
    $show_signup = false; // Set variabel untuk menentukan apakah tombol signup ditampilkan atau tidak
} else {
    // Jika pengguna belum login, tampilkan tombol signup dan login
    $navbar_text = 'Login';
    $navbar_link = 'index.php?page=loginUser'; // Ganti dengan halaman login Anda
    $show_signup = true; // Set variabel untuk menentukan apakah tombol signup ditampilkan atau tidak
}

// Jika sudah login, tampilkan konten halaman dokter.php di sini
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, 
    initial-scale=1.0">

    <!-- Bootstrap offline -->

    <link rel="stylesheet" href="assets/css/bootstrap.css"> 

    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">   

    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa; /* Warna latar belakang */
        }
        .navbar-brand {
            font-size: 24px; /* Ukuran font judul */
            font-weight: bold; /* Ketebalan font judul */
        }
        .navbar-nav .nav-link {
            font-size: 18px; /* Ukuran font link */
        }
        .navbar-nav .nav-item {
            margin-right: 10px; /* Ruang antar item navigasi */
        }
        .navbar-nav.ms-auto .nav-link {
            font-weight: bold; /* Ketebalan font tombol login/logout */
        }
        .navbar-nav.ms-auto .nav-item:last-child {
            margin-right: 0; /* Hapus margin kanan dari tombol login/logout terakhir */
        }
        
    </style>
    
</head>
<body>

<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
        Sistem Informasi Poliklinik
        </a>
        <button class="navbar-toggler"
        type="button" data-bs-toggle="collapse"
        data-bs-target="#navbarNavDropdown"
        aria-controls="navbarNavDropdown" aria-expanded="false"
        aria-label="Toggle navigation">
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" aria-current="page" href="index.php">
                    Home
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                    Data Master
                </a>
            <ul class="dropdown-menu">
                <li>
                    <a class="dropdown-item" href="index.php?page=dokter">
                        Dokter
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="index.php?page=pasien">
                        Pasien
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="index.php?page=obat">
                        Obat
                    </a>
                </li>
            </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link" 
                href="index.php?page=periksa">
                    Periksa
                </a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto"> <!-- ms-auto untuk menggeser ke kanan -->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo $navbar_link; ?>">
                    <?php echo $navbar_text; ?>
                </a>
            </li>
            <?php if ($show_signup) : ?> <!-- Tampilkan tombol signup jika variabel $show_signup bernilai true -->
            <li class="nav-item">
                <a class="nav-link" href="index.php?page=registrasiUser">Signup</a>
            </li>
            <?php endif; ?>
        </ul>
        </div>
    </div>
    </nav>

  
    <main role="main" class="container">
        <?php
            if (isset($_GET['page'])) {
                ?>
                    <h2><?php echo ucwords($_GET['page']) ?></h2>
                <?php
                include($_GET['page'] . ".php");
            } else {
                ?>
                <div class="welcome-section">
                    <h1 style="text-align:center;" >Selamat Datang di Sistem Informasi Poliklinik</h1>
                    <p style="text-align:center;">
                        Sistem Informasi Poliklinik kami menyediakan layanan untuk mengelola data dokter, pasien, obat, dan pemeriksaan dengan mudah dan efisien.
                    </p>
                    <p style="text-align:center;">
                        Silakan gunakan navigasi di atas untuk mengakses berbagai fitur yang tersedia. Jika Anda belum memiliki akun, silakan daftar terlebih dahulu.
                    </p>
                </div>
                <?php
            }
        ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
