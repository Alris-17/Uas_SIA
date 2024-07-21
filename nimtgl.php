<?php
include 'koneksi.php';

$message = '';
$nilai_records = [];
$total_nilai = 0;

// Proses pencarian berdasarkan NIM dan Tanggal
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'] ?? '';
    $tanggal = $_POST['tanggal'] ?? '';

    if ($tanggal) {
        $sql = "SELECT n.id_nilai, n.nim, n.kode_mk, n.nilai, n.tanggal, m.nama_mahasiswa, mk.nama_mata_kuliah 
                FROM Nilai n 
                JOIN Mahasiswa m ON n.nim = m.nim 
                JOIN Mata_Kuliah mk ON n.kode_mk = mk.kode_mk
                WHERE n.tanggal = ?";

        if ($nim) {
            $sql .= " AND n.nim = ?";
        }

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            if ($nim) {
                $stmt->bind_param("ss", $tanggal, $nim);
            } else {
                $stmt->bind_param("s", $tanggal);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $nilai_records[] = $row;
                $total_nilai += $row['nilai'];  // Akumulasi nilai
            }
            $stmt->close();
        } else {
            $message = "Query database gagal.";
        }
    } else {
        $message = "Tanggal harus diisi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Nilai Berdasarkan NIM dan Tanggal</title>
    <link rel="stylesheet" href="dosen1.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="menu">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="mahasiswa.php" class="link-button">Manajemen Mahasiswa</a>
                <a href="dosen.php" class="link-button">Manajemen Dosen</a>
                <a href="matakuliah.php" class="link-button">Manajemen Mata Kuliah</a>
                <a href="nilai.php" class="link-button">Manajemen Nilai</a>
                <a href="pernim.php" class="link-button">Melihat PerNIM</a>
                <a href="pertgl.php" class="link-button">Melihat Tanggal</a>
                <a href="periode.php" class="link-button">Melihat Periode</a>
                <a href="nimtgl.php" class="link-button">Melihat NIM Tanggal</a>
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
        <h2 align="center">Pencarian Nilai Berdasarkan NIM dan Tanggal</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="nim">NIM Mahasiswa:</label>
                <input type="text" id="nim" name="nim" value="<?php echo htmlspecialchars($_POST['nim'] ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo htmlspecialchars($_POST['tanggal'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Cari</button>
            </div>
        </form>

        <?php if (!empty($nilai_records)): ?>
            <h2 align="center">Hasil Pencarian NIM <?php echo htmlspecialchars($nim); ?> pada <?php echo htmlspecialchars($tanggal); ?></h2>
            <table>
                <tr>
                    <th>ID Nilai</th>
                    <th>NIM Mahasiswa</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kode Mata Kuliah</th>
                    <th>Nama Mata Kuliah</th>
                    <th>Tanggal</th>
                    <th>Nilai</th>
                </tr>
                <?php foreach ($nilai_records as $record): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($record['id_nilai']); ?></td>
                        <td><?php echo htmlspecialchars($record['nim']); ?></td>
                        <td><?php echo htmlspecialchars($record['nama_mahasiswa']); ?></td>
                        <td><?php echo htmlspecialchars($record['kode_mk']); ?></td>
                        <td><?php echo htmlspecialchars($record['nama_mata_kuliah']); ?></td>
                        <td><?php echo htmlspecialchars($record['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($record['nilai']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <h3 align="center">Total Nilai: <?php echo htmlspecialchars($total_nilai); ?></h3>
        <?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <p>Tidak ada nilai yang ditemukan untuk NIM dan tanggal yang diberikan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
