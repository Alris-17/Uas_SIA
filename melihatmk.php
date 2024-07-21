<?php
include 'koneksi.php';

// Mendapatkan data Mata Kuliah dengan Nama Dosen
$sql = "SELECT m.kode_mk, m.nama_mata_kuliah, m.sks, d.nama AS nama_dosen
        FROM Mata_Kuliah m
        LEFT JOIN Dosen d ON m.nip = d.nip";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mata Kuliah</title>
    <link rel="stylesheet" type="text/css" href="dosen1.css">
</head>
<body>
<div class="dashboard-container">
        <h2>Dashboard</h2>
        <p class="welcome-message">Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <div class="menu">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="mahasiswa.php" class="link-button">Manajemen Mahasiswa</a>
                <a href="dosen.php" class="link-button">Manajemen Dosen</a>
                <a href="matakuliah.php" class="link-button">Manajemen Mata Kuliah</a>
                <a href="nilai.php" class="link-button">Manajemen Nilai</a>
            <?php elseif ($_SESSION['role'] == 'mahasiswa'): ?>
                <a href="mahasiswa.php" class="link-button">Mengisi Profil Mahasiswa</a>
                <a href="melihatmk.php" class="link-button">Lihat Mata Kuliah</a>
                <a href="melihatnilai.php" class="link-button">Lihat Nilai</a>
            <?php elseif ($_SESSION['role'] == 'dosen'): ?>
                <a href="dosen.php" class="link-button">Mengisi Profil Dosen</a>
                <a href="matakuliah.php" class="link-button">Mengisi Mata Kuliah</a>
                <a href="nilai.php" class="link-button">Input Nilai</a>
            <?php endif; ?>
        </div>
        <a href="logout.php" class="logout-button">Logout</a>
    </div>
    <div class="container">
        <h2 align="center">Daftar Mata Kuliah</h2>
        <?php
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Kode MK</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Nama Dosen</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["kode_mk"]."</td>
                    <td>".$row["nama_mata_kuliah"]."</td>
                    <td>".$row["sks"]."</td>
                    <td>".$row["nama_dosen"]."</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada mata kuliah yang tersedia.</p>";
        }
        ?>
    </div>
</body>
</html>
