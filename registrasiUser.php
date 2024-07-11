<?php
include_once("koneksi.php");

$username = '';
$password = '';
$errors = '';
$success_message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];

    // Memeriksa apakah semua field telah diisi
    if (empty($username) || empty($password) || empty($cpassword)) {
        $errors = "Silakan lengkapi semua field.";
    } else {
        // Memeriksa apakah password cocok dengan konfirmasi password
        if ($password !== $cpassword) {
            $errors = "password tidak cocok.";
        } else {
            // Hash password menggunakan password_hash()
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Query untuk mengecek apakah username sudah terdaftar
            $check_username_query = "SELECT * FROM usertable WHERE username='$username'";
            $check_username_result = mysqli_query($mysqli, $check_username_query);

            if (mysqli_num_rows($check_username_result) > 0) {
                $errors = "Username sudah terdaftar. Gunakan username lain.";
            } else {
                // Jika username belum terdaftar, lakukan operasi insert
                $insert_query = "INSERT INTO usertable (username, password) VALUES ('$username', '$hashed_password')";
                $insert_result = mysqli_query($mysqli, $insert_query);
                
                if ($insert_result) {
                    // Jika registrasi berhasil, arahkan ke halaman login dan tampilkan pesan sukses
                    $success_message = "User berhasil ditambahkan.";
                    echo "<script>
                    alert('$success_message');
                    document.location='index.php?page=loginUser';
                    </script>";
                } else {
                    $errors = "Gagal melakukan registrasi. Silakan coba lagi.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <!-- Bootstrap offline -->
    <link rel="stylesheet" href="assets/css/bootstrap.css"> 
    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">          
    <style>
        .container {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            border-radius: 1px;
            background-color: #f9f9f9;
        }

        /* Penyesuaian tata letak tombol Simpan */
        .form-group .btn-primary {
            width: 50%;
            border-radius: 5px;
            margin-top: 10px; /* Menambahkan ruang di atas tombol */
        }

        /* Penyesuaian tata letak teks Registrasi */
        .register-link {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 15px; /* Menambahkan ruang di bawah teks */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $errors; ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success_message)) : ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>
                <form class="form" method="POST" action="">
                    <div class="form-group">
                        <label for="inputUsername" class="form-label fw-bold">Username *</label>
                        <input type="text" class="form-control" name="username" id="inputUsername" placeholder="Username" value="<?php echo $username; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputPassword" class="form-label fw-bold">Password *</label>
                        <input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password" value="<?php echo $password; ?>">
                    </div>
                    <div class="form-group">
                        <label for="inputCPassword" class="form-label fw-bold">Konfirmasi Password *</label>
                        <input type="password" class="form-control" name="cpassword" id="inputCPassword" placeholder="Konfirmasi Password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
                    </div>
                    <div class="register-link">
                       sudah punya akun? <a href="index.php?page=loginUser">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
