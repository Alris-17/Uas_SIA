<?php
include 'koneksi.php';

// Mendapatkan data Nilai dengan nama mata kuliah, nama dosen, dan nama mahasiswa
$sql = "SELECT n.id_nilai, n.nim, n.kode_mk, n.nilai, 
               m.nama_mata_kuliah, 
               d.nama AS nama_dosen,
               ma.nama_mahasiswa
        FROM Nilai n
        JOIN Mata_Kuliah m ON n.kode_mk = m.kode_mk
        LEFT JOIN Dosen d ON m.nip = d.nip
        LEFT JOIN Mahasiswa ma ON n.nim = ma.nim";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Nilai</title>
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
        <h2 align="center">Daftar Nilai</h2>
        
        <table>
            <tr>
                <th>No</th>
                <th>NIM Mahasiswa</th>
                <th>Nama Mahasiswa</th>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>Nama Dosen</th>
                <th>Nilai</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['id_nilai']}</td>
                        <td>{$row['nim']}</td>
                        <td>{$row['nama_mahasiswa']}</td>
                        <td>{$row['kode_mk']}</td>
                        <td>{$row['nama_mata_kuliah']}</td>
                        <td>{$row['nama_dosen']}</td>
                        <td>{$row['nilai']}</td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada nilai yang tersedia.</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
