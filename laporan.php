<?php
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");
include 'config.php';

$result = $conn->query("
    SELECT s.nama, s.kelas, a.tanggal, a.status
    FROM siswa s
    LEFT JOIN absensi a ON s.id = a.siswa_id
    ORDER BY s.nama, a.tanggal
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Laporan Absensi</title>
    <style>
        /* Custom warna untuk status absensi */
        .status-hadir { background-color: #d4edda; color: #155724; }
        .status-izin { background-color: #fff3cd; color: #856404; }
        .status-sakit { background-color: #f8d7da; color: #721c24; }
        .status-alpha { background-color: #f5c6cb; color: #721c24; }
        .status-belum { background-color: #e2e3e5; color: #383d41; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Laporan Absensi</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="siswa.php">Kelola Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="absensi.php">Mark Absensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="laporan.php">Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Container Utama -->
    <div class="main-container">
        <h1>Laporan Absensi Siswa</h1>
        <p class="text-center text-muted">Lihat rekam jejak absensi semua siswa di bawah ini.</p>

        <!-- Tabel Laporan -->
        <div class="table-responsive">
            <table class="table table-striped table-hover laporan-table">
                <thead>
                    <tr>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()) { 
                        $status_class = 'status-belum';
                        if ($row['status'] == 'hadir') $status_class = 'status-hadir';
                        elseif ($row['status'] == 'izin') $status_class = 'status-izin';
                        elseif ($row['status'] == 'sakit') $status_class = 'status-sakit';
                        elseif ($row['status'] == 'alpha') $status_class = 'status-alpha';
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['kelas']); ?></td>
                            <td><?php echo $row['tanggal'] ? htmlspecialchars($row['tanggal']) : 'Belum ada'; ?></td>
                            <td><span class="badge <?php echo $status_class; ?>"><?php echo $row['status'] ? htmlspecialchars($row['status']) : 'Belum ada'; ?></span></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
            <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>Sistem Absensi Siswa &copy; 2023</p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>