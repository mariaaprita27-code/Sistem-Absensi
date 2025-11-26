<?php
session_start();
if (!isset($_SESSION['user'])) header("Location: index.php");
include 'config.php';

$tanggal = date('Y-m-d');
$message = '';

// Simpan absensi
if (isset($_POST['simpan'])) {
    foreach ($_POST['status'] as $siswa_id => $status) {
        $stmt = $conn->prepare("INSERT INTO absensi (siswa_id, tanggal, status) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE status = ?");
        $stmt->bind_param("isss", $siswa_id, $tanggal, $status, $status);
        $stmt->execute();
    }
    $message = "Absensi untuk tanggal $tanggal berhasil disimpan!";
}

$result = $conn->query("SELECT * FROM siswa");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Mark Absensi</title>
    <style>
        /* Custom warna untuk status absensi */
        .status-hadir { background-color: #d4edda; color: #155724; }
        .status-izin { background-color: #fff3cd; color: #856404; }
        .status-sakit { background-color: #f8d7da; color: #721c24; }
        .status-alpha { background-color: #f5c6cb; color: #721c24; }
        /* Card hover effect */
        .siswa-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .siswa-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Mark Absensi</a>
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
                        <a class="nav-link active" href="absensi.php">Mark Absensi</a>
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
    <div class="main-container">
        <h1>Mark Absensi Siswa</h1>
        <p class="text-center text-muted">Pilih status absensi untuk setiap siswa pada tanggal <strong><?php echo $tanggal; ?></strong>.</p>

        <!-- Alert untuk pesan -->
        <?php if ($message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Form Absensi -->
        <form method="POST">
            <div class="row">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card siswa-card">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['nama']); ?></h5>
                                <p class="card-text text-muted">Kelas: <?php echo htmlspecialchars($row['kelas']); ?> | NIS: <?php echo htmlspecialchars($row['nis']); ?></p>
                                <select name="status[<?php echo $row['id']; ?>]" class="form-select absensi-select" required>
                                    <option value="hadir" class="status-hadir">Hadir</option>
                                    <option value="izin" class="status-izin">Izin</option>
                                    <option value="sakit" class="status-sakit">Sakit</option>
                                    <option value="alpha" class="status-alpha">Alpha</option>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div class="text-center mt-4">
                <button type="submit" name="simpan" class="btn btn-primary btn-lg">Simpan Absensi</button>
            </div>
        </form>

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