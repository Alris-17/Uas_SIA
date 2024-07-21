<?php
include 'koneksi.php';

$message = '';

// Tambah dan Update Nilai
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $id_nilai = $_POST['id_nilai'] ?? null;
    $nim = $_POST['nim'];
    $kode_mk = $_POST['kode_mk'];
    $nilai = $_POST['nilai'];
    $tanggal = $_POST['tanggal']; // Tambahkan tanggal

    if ($action == 'add') {
        $sql = "INSERT INTO Nilai (nim, kode_mk, nilai, tanggal) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $nim, $kode_mk, $nilai, $tanggal); // Bind tanggal
            if ($stmt->execute()) {
                $message = "Nilai berhasil ditambahkan.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Query database gagal.";
        }
    } elseif ($action == 'update') {
        $sql = "UPDATE Nilai SET nim = ?, kode_mk = ?, nilai = ?, tanggal = ? WHERE id_nilai = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssssi", $nim, $kode_mk, $nilai, $tanggal, $id_nilai); // Bind tanggal
            if ($stmt->execute()) {
                $message = "Nilai berhasil diupdate.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Query database gagal.";
        }
    }
}

// Hapus Nilai
if (isset($_GET['delete'])) {
    $id_nilai = $_GET['id_nilai'];
    $sql = "DELETE FROM Nilai WHERE id_nilai = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_nilai);
        if ($stmt->execute()) {
            $message = "Nilai berhasil dihapus.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Query database gagal.";
    }
}

// Mendapatkan data Nilai untuk diedit
$nilai = null;
if (isset($_GET['edit'])) {
    $id_nilai = $_GET['id_nilai'];
    $sql = "SELECT * FROM Nilai WHERE id_nilai = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $id_nilai);
        $stmt->execute();
        $result = $stmt->get_result();
        $nilai = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Nilai</title>
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
    <div class="container">
        <h2 align="center">Manajemen Nilai</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="nim">NIM Mahasiswa:</label>
                <select id="nim" name="nim" required>
                    <option value="">Pilih Mahasiswa</option>
                    <?php
                    $mahasiswa_query = "SELECT nim, nama_mahasiswa FROM Mahasiswa";
                    $mahasiswa_result = $conn->query($mahasiswa_query);
                    while ($mahasiswa = $mahasiswa_result->fetch_assoc()) {
                        echo "<option value='".$mahasiswa['nim']."'".(isset($nilai['nim']) && $nilai['nim'] == $mahasiswa['nim'] ? ' selected' : '').">".$mahasiswa['nim']."- ".$mahasiswa['nama_mahasiswa']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="kode_mk">Kode Mata Kuliah:</label>
                <select id="kode_mk" name="kode_mk" required>
                    <option value="">Pilih Mata Kuliah</option>
                    <?php
                    $mata_kuliah_query = "SELECT kode_mk, nama_mata_kuliah FROM Mata_Kuliah";
                    $mata_kuliah_result = $conn->query($mata_kuliah_query);
                    while ($mata_kuliah = $mata_kuliah_result->fetch_assoc()) {
                        echo "<option value='".$mata_kuliah['kode_mk']."'".(isset($nilai['kode_mk']) && $nilai['kode_mk'] == $mata_kuliah['kode_mk'] ? ' selected' : '').">".$mata_kuliah['kode_mk']."- ".$mata_kuliah['nama_mata_kuliah']."</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nilai">Nilai:</label>
                <input type="text" id="nilai" name="nilai" value="<?php echo $nilai['nilai'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" id="tanggal" name="tanggal" value="<?php echo $nilai['tanggal'] ?? ''; ?>" required>
            </div>
            <?php if ($nilai): ?>
                <input type="hidden" name="action" value="update">
            <?php else: ?>
                <input type="hidden" name="action" value="add">
            <?php endif; ?>
            <div class="form-group">
                <button type="submit"><?php echo $nilai ? 'Update' : 'Tambah'; ?></button>
            </div>
        </form>

        <h2 align="center">Daftar Nilai</h2>
        <?php
        $sql = "SELECT n.id_nilai, n.nim, n.kode_mk, n.nilai, n.tanggal, m.nama_mahasiswa, mk.nama_mata_kuliah 
                FROM Nilai n 
                JOIN Mahasiswa m ON n.nim = m.nim 
                JOIN Mata_Kuliah mk ON n.kode_mk = mk.kode_mk";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>
                        <tr><th>ID Nilai</th>
                        <th>NIM Mahasiswa</th>
                        <th>Nama Mahasiswa</th>
                        <th>Kode Mata Kuliah</th>
                        <th>Nama Mata Kuliah</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>
                        <th>Aksi</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["id_nilai"]."</td>
                    <td>".$row["nim"]."</td>
                    <td>".$row["nama_mahasiswa"]."</td>
                    <td>".$row["kode_mk"]."</td>
                    <td>".$row["nama_mata_kuliah"]."</td>
                    <td>".$row["nilai"]."</td>
                    <td>".$row["tanggal"]."</td>
                    <td>
                        <a href='?edit=1&id_nilai=".$row["id_nilai"]."'>Edit</a> | 
                        <a href='?delete=1&id_nilai=".$row["id_nilai"]."'>Delete</a>
                    </td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Tidak ada nilai yang tersedia.</p>";
        }
        ?>
    </div>
</body>
</html>
