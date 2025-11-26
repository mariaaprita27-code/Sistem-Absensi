<?php
session_start();
// Pastikan pengguna sudah login
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit(); // Selalu gunakan exit() setelah header redirect
}

// Sertakan file konfigurasi database
include 'config.php';

// --- Logika Penanganan POST/GET ---

// 1. Tambah Siswa (Menggunakan Prepared Statement untuk keamanan)
if (isset($_POST['tambah'])) {
    // Sanitize input
    $nama = trim($_POST['nama']);
    $nis = trim($_POST['nis']);
    $kelas = trim($_POST['kelas']);

    if (!empty($nama) && !empty($nis) && !empty($kelas)) {
        $stmt = $conn->prepare("INSERT INTO siswa (nama, nis, kelas) VALUES (?, ?, ?)");
        // 'sss' berarti tiga parameter yang di-bind adalah string
        $stmt->bind_param("sss", $nama, $nis, $kelas);
        
        if ($stmt->execute()) {
            // Opsional: pesan sukses
            // echo "<script>alert('Siswa berhasil ditambahkan!');</script>";
        } else {
             // Opsional: pesan error
             // echo "<script>alert('Gagal menambahkan siswa: " . $stmt->error . "');</script>";
        }
        $stmt->close();
    }
}

// 2. Hapus Siswa (Menggunakan Prepared Statement untuk keamanan)
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    
    // Pastikan ID adalah integer
    if (filter_var($id, FILTER_VALIDATE_INT)) {
        $stmt = $conn->prepare("DELETE FROM siswa WHERE id = ?");
        // 'i' berarti parameter yang di-bind adalah integer
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        // Redirect untuk menghilangkan parameter GET setelah operasi, mencegah penghapusan berulang
        header("Location: siswa.php");
        exit();
    }
}

// 3. Ambil Data Siswa
// Ambil semua data siswa untuk ditampilkan
$result = $conn->query("SELECT id, nama, nis, kelas FROM siswa ORDER BY kelas, nama ASC");

// Tutup koneksi (opsional, tergantung pada alur aplikasi, tapi baik untuk dilakukan)
// $conn->close(); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa | Dashboard Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        /* Gaya Kustom Estetik */
        :root {
            --primary-color: #007bff; /* Biru Primer */
            --secondary-color: #6c757d; /* Abu-abu Sekunder */
            --info-color: #17a2b8; /* Biru Muda Info */
            --success-color: #28a745; /* Hijau Sukses */
            --danger-color: #dc3545; /* Merah Bahaya */
            --warning-color: #ffc107; /* Kuning Peringatan */
            --bg-light: #f8f9fa; /* Latar belakang terang */
            --card-bg: #ffffff; /* Latar belakang kartu */
        }

        body {
            background-color: var(--bg-light); /* Warna latar belakang ringan */
        }

        .navbar {
            background-color: var(--primary-color); /* Navbar berwarna primary */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .navbar .nav-link {
            color: white !important;
            margin-right: 15px;
            transition: color 0.3s;
        }

        .navbar .nav-link:hover {
            color: var(--warning-color) !important; /* Efek hover */
        }

        .siswa-container {
            padding-top: 30px;
        }
        
        /* Kartu Tambah Siswa */
        .tambah-siswa-card {
            background-color: var(--card-bg);
            border-left: 5px solid var(--success-color); /* Aksen warna sukses */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        /* Daftar Siswa Grid */
        .siswa-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); /* Responsif grid */
            gap: 20px;
        }

        /* Kartu Siswa */
        .siswa-card {
            background-color: var(--card-bg);
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden; /* Pastikan border-radius diterapkan ke semua elemen anak */
        }

        .siswa-card:hover {
            transform: translateY(-5px); /* Efek melayang */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
        
        .siswa-card-header {
            background-color: var(--info-color); /* Header kartu berwarna info */
            color: white;
            padding: 10px 15px;
            font-size: 1.1em;
            font-weight: bold;
        }

        .siswa-card-body p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-chalkboard-teacher me-2"></i>Dashboard Absensi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="siswa.php"><i class="fas fa-users me-1"></i>Kelola Siswa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="absensi.php"><i class="fas fa-clipboard-list me-1"></i>Mark Absensi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="laporan.php"><i class="fas fa-chart-bar me-1"></i>Laporan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-warning text-dark px-3 py-1 ms-3" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="container siswa-container">
        
        <h1 class="text-center mb-4 text-primary"><i class="fas fa-user-plus me-2"></i>Kelola Siswa</h1>
        
        <hr class="mb-5">

        <div class="card p-4 mb-5 tambah-siswa-card">
            <h2 class="card-title text-success mb-3"><i class="fas fa-plus-circle me-2"></i>Tambah Siswa Baru</h2>
            <form method="POST" class="row g-3">
                <div class="col-md-4">
                    <label for="nama" class="form-label">Nama Siswa</label>
                    <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Lengkap" required>
                </div>
                <div class="col-md-4">
                    <label for="nis" class="form-label">NIS (Nomor Induk Siswa)</label>
                    <input type="text" class="form-control" id="nis" name="nis" placeholder="NIS" required>
                </div>
                <div class="col-md-4">
                    <label for="kelas" class="form-label">Kelas</label>
                    <input type="text" class="form-control" id="kelas" name="kelas" placeholder="Contoh: X RPL 1" required>
                </div>
                <div class="col-12 mt-4">
                    <button type="submit" name="tambah" class="btn btn-success"><i class="fas fa-save me-2"></i>Tambah Siswa</button>
                </div>
            </form>
        </div>
        
        <h2 class="text-info mb-4"><i class="fas fa-list-alt me-2"></i>Daftar Siswa Terdaftar</h2>
        
        <div class="siswa-grid">
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <div class="siswa-card">
                        <div class="siswa-card-header">
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </div>
                        <div class="card-body siswa-card-body">
                            <p class="mb-1"><strong><i class="fas fa-id-card me-1 text-secondary"></i> NIS:</strong> <?php echo htmlspecialchars($row['nis']); ?></p>
                            <p class="mb-3"><strong><i class="fas fa-school me-1 text-secondary"></i> Kelas:</strong> <?php echo htmlspecialchars($row['kelas']); ?></p>
                            <div class="aksi mt-2 text-end">
                                <a href="?hapus=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Yakin ingin menghapus siswa bernama <?php echo addslashes(htmlspecialchars($row['nama'])); ?>?')">
                                   <i class="fas fa-trash-alt me-1"></i>Hapus
                                </a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>Belum ada data siswa yang terdaftar.
                    </div>
                </div>
            <?php } ?>
        </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>