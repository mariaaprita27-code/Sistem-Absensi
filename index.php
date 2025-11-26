<?php
session_start();
// Sertakan file konfigurasi database
include 'config.php'; 

// Inisialisasi variabel error
$error = '';

// Cek jika sudah ada sesi login, arahkan ke dashboard.php
if (isset($_SESSION['user'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $input_password = $_POST['password'];

    // --- KEAMANAN PENTING: Menggunakan MD5 seperti kode asli ---
    $password_hashed = md5($input_password);

    // 1. Kueri database untuk memverifikasi username dan password MD5
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    
    // Perhatikan: $password_hashed digunakan di sini
    if ($stmt === false) {
        $error = "Gagal menyiapkan statement SQL. Cek koneksi atau struktur tabel.";
    } else {
        $stmt->bind_param("ss", $username, $password_hashed);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['user'] = $result->fetch_assoc();
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Username atau password salah!";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistem Absensi Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* Palet Warna: Biru Tua (Primer), Kuning Emas (Aksen) */
        :root {
            --primary-blue: #1a237e; /* Biru Tua Sekolah */
            --accent-gold: #ffc107; /* Kuning Emas */
            --bg-light: #f5f5f5;
        }

        body {
            background: linear-gradient(135deg, var(--bg-light) 0%, #e8eaf6 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 380px;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(26, 35, 126, 0.2); /* Shadow dengan warna biru tua */
            background-color: #ffffff;
            border-top: 5px solid var(--primary-blue);
        }

        .logo-pendidikan {
            color: var(--primary-blue);
            border: 3px solid var(--accent-gold);
            border-radius: 50%;
            padding: 10px;
            background-color: white;
        }

        .login-header {
            color: var(--primary-blue);
            margin-top: 1rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .form-control {
            border-radius: 8px;
            border: 1px solid #ced4da;
            padding: 0.75rem 1.25rem;
        }
        
        .btn-login-custom {
            background-color: var(--primary-blue); 
            border-color: var(--primary-blue);
            border-radius: 8px;
            padding: 0.75rem 0;
            font-weight: bold;
            transition: background-color 0.3s;
        }

        .btn-login-custom:hover {
            background-color: #0d47a1;
            border-color: #0d47a1;
        }
    </style>
</head>
<body>
    
    <div class="login-card text-center">
        
        <i class="fas fa-graduation-cap fa-4x logo-pendidikan mb-3"></i>
        
        <h3 class="login-header">Login Absensi</h3>
        
        <?php if (!empty($error)) { ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php } ?>
        
        <form method="POST">
            
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="username" name="username" placeholder="Username Anda" required>
                <label for="username"><i class="fas fa-user me-2"></i>Username</label>
            </div>
            
            <div class="form-floating mb-4">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password Anda" required>
                <label for="password"><i class="fas fa-lock me-2"></i>Password</label>
            </div>
            
            <button type="submit" class="btn btn-login-custom w-100">
                <i class="fas fa-sign-in-alt me-2"></i> Masuk
            </button>
            
        </form>
        
        <p class="mt-4 text-muted small">Sistem Informasi Absensi</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>