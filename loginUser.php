<?php
include_once("koneksi.php");

$errors = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk mencari user berdasarkan username dan password
    $query = "SELECT * FROM usertable WHERE username='$username'";
    $result = mysqli_query($mysqli, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Jika user ditemukan dan password cocok, set session dan arahkan ke halaman utama
            $_SESSION['loggedin'] = true;
            header("Location: index.php");
            exit();
        } else {
            // Jika password tidak cocok, tampilkan pesan error
            $errors = "Username atau password salah. Silakan coba lagi.";
        }
    } else {
        // Jika user tidak ditemukan, tampilkan pesan error
        $errors = "Username atau password salah. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Login</title>
    <!-- Bootstrap offline -->
    <link rel="stylesheet" href="assets/css/bootstrap.css"> 
    <!-- Bootstrap Online -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
    rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
    crossorigin="anonymous">  
    <style>
        .login-box {
            max-width: 400px;
            margin: auto;
            padding: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .login-box h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .login-box .form-group {
            margin-bottom: 20px;
        }
        .login-box .form-group label {
            font-weight: bold;
        }
        .login-box .form-control {
            border-radius: 5px;
        }
        .login-box .btn-primary {
            width: 100%;
            border-radius: 5px;
        }
        .register-link {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Login</h2>
        <form class="form" method="POST" action="">
            <div class="form-group">
                <label for="inputusername">Username *</label>
                <input type="text" class="form-control" id="inputusername" name="username" required>
            </div>
            <div class="form-group">
                <label for="inputPassword">Password *</label>
                <input type="password" class="form-control" id="inputPassword" name="password" required>
            </div>
            <?php if (!empty($errors)) : ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $errors; ?>
                </div>
            <?php endif; ?>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="register-link">
            Belum punya akun? <a href="index.php?page=registrasiUser">Daftar</a>
        </div>
    </div>

    <!-- Bootstrap JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>
