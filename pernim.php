<?php
include 'koneksi.php';

$search_result = null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
    $nim = $_POST['nim'];

    if (!empty($nim)) {
        $sql = "SELECT * FROM Mahasiswa WHERE nim = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nim);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $search_result = $result->fetch_assoc();
        } else {
            $message = "Tidak ada mahasiswa dengan NIM $nim.";
        }

        $stmt->close();
    } else {
        $message = "Silakan masukkan NIM untuk pencarian.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pencarian Mahasiswa</title>
    <link rel="stylesheet" type="text/css" href="dosen1.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="menu">
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <a href="mahasiswa.php" class="link-button">Manajemen Mahasiswa</a>
                <a href="dosen.php" class="link-button">Manajemen Dosen</a>
                <a href="matakuliah.php" class="link-button">Manajemen Mata Kuliah</a>
                <a href="nilai.php" class="link-button">Manajemen Nilai</a>
                <a href="pernim.php" class="link-button">Melihat NIM</a>
                <a href="pertgl.php" class="link-button">Melihat tanggal</a>
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
    
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
	<h2 align="center">Pencarian Mahasiswa</h2>
        <div class="form-group">
            <label for="nim">Masukkan NIM:</label>
            <input type="text" id="nim" name="nim" required>
        </div>
        <div class="form-group">
            <button type="submit" name="search">Cari</button>
        </div>
    </form>

    <?php if ($message): ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <?php if ($search_result): ?>
        <h2 align="center">Hasil Pencarian NIM <?php echo htmlspecialchars($nim); ?></h2>
        <table>
            <tr>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Jenis Kelamin</th>
                <th>No Telepon</th>
                <th>Alamat</th>
            </tr>
            <tr>
                <td><?php echo htmlspecialchars($search_result['nim']); ?></td>
                <td><?php echo htmlspecialchars($search_result['nama_mahasiswa']); ?></td>
                <td><?php echo htmlspecialchars($search_result['jenis_kelamin']); ?></td>
                <td><?php echo htmlspecialchars($search_result['no_telp']); ?></td>
                <td><?php echo htmlspecialchars($search_result['alamat']); ?></td>
            </tr>
        </table>
    <?php endif; ?>
</body>
</html>
