<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include 'config.php';

// Statistik sederhana dari database
$total_siswa = $conn->query("SELECT COUNT(*) as count FROM siswa")->fetch_assoc()['count'];
$total_absensi_hari_ini = $conn->query("SELECT COUNT(*) as count FROM absensi WHERE tanggal = CURDATE()")->fetch_assoc()['count'];
$total_hadir = $conn->query("SELECT COUNT(*) as count FROM absensi WHERE status = 'hadir' AND tanggal = CURDATE()")->fetch_assoc()['count'];
$total_alpha = $conn->query("SELECT COUNT(*) as count FROM absensi WHERE status = 'alpha' AND tanggal = CURDATE()")->fetch_assoc()['count'];
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Custom CSS (opsional, jika ingin tambahan style) -->
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        .stat-card {
            transition: transform 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
    </style>

    <title>Dashboard Absensi Siswa</title>
</head>
<body>
    <!-- Navbar Bootstrap -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Dashboard Absensi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="siswa.php">Kelola Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="absensi.php">Mark Absensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php">Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Container Utama -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Dashboard Absensi Siswa</h1>
                
                <!-- Welcome Alert -->
                <div class="alert alert-info text-center" role="alert">
                    Selamat datang, <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong>! Kelola absensi siswa dengan mudah.
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-5">
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center bg-light">
                    <div class="card-body">
                        <h3 class="card-title text-primary"><?php echo $total_siswa; ?></h3>
                        <p class="card-text">Total Siswa</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center bg-light">
                    <div class="card-body">
                        <h3 class="card-title text-success"><?php echo $total_absensi_hari_ini; ?></h3>
                        <p class="card-text">Absensi Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center bg-light">
                    <div class="card-body">
                        <h3 class="card-title text-info"><?php echo $total_hadir; ?></h3>
                        <p class="card-text">Hadir Hari Ini</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card text-center bg-light">
                    <div class="card-body">
                        <h3 class="card-title text-danger"><?php echo $total_alpha; ?></h3>
                        <p class="card-text">Alpha Hari Ini</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-12">
                <div class="card bg-dark text-white">
                    <div class="card-body text-center">
                        <h2 class="card-title">Aksi Cepat</h2>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="siswa.php" class="btn btn-primary btn-lg w-100">Kelola Siswa</a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="absensi.php" class="btn btn-success btn-lg w-100">Mark Absensi</a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="laporan.php" class="btn btn-info btn-lg w-100">Lihat Laporan</a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="logout.php" class="btn btn-danger btn-lg w-100">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center mt-5 text-muted">
            <p>Sistem Absensi Siswa &copy; 2023</p>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>