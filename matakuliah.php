<?php
include 'koneksi.php';

$message = '';

// Tambah dan Update Mata Kuliah
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $kode_mk = $_POST['kode_mk'];
    $nama_mata_kuliah = $_POST['nama_mata_kuliah'];
    $sks = $_POST['sks'];
    $nip = $_POST['nip'];

    if ($action == 'add') {
        $sql = "INSERT INTO Mata_Kuliah (kode_mk, nama_mata_kuliah, sks, nip) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssis", $kode_mk, $nama_mata_kuliah, $sks, $nip);
            if ($stmt->execute()) {
                $message = "Mata Kuliah berhasil ditambahkan.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Query database gagal.";
        }
    } elseif ($action == 'update') {
        $sql = "UPDATE Mata_Kuliah SET nama_mata_kuliah = ?, sks = ?, nip = ? WHERE kode_mk = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("siss", $nama_mata_kuliah, $sks, $nip, $kode_mk);
            if ($stmt->execute()) {
                $message = "Mata Kuliah berhasil diupdate.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Query database gagal.";
        }
    }
}

// Hapus Mata Kuliah
if (isset($_GET['delete'])) {
    $kode_mk = $_GET['kode_mk'];
    $sql = "DELETE FROM Mata_Kuliah WHERE kode_mk = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $kode_mk);
        if ($stmt->execute()) {
            $message = "Mata Kuliah berhasil dihapus.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Query database gagal.";
    }
}

// Mendapatkan data Mata Kuliah untuk diedit
$mata_kuliah = null;
if (isset($_GET['edit'])) {
    $kode_mk = $_GET['kode_mk'];
    $sql = "SELECT * FROM Mata_Kuliah WHERE kode_mk = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $kode_mk);
        $stmt->execute();
        $result = $stmt->get_result();
        $mata_kuliah = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Mata Kuliah</title>
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
        <h2 align="center">Manajemen Mata Kuliah</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="kode_mk">Kode Mata Kuliah:</label>
                <input type="text" id="kode_mk" name="kode_mk" value="<?php echo htmlspecialchars($mata_kuliah['kode_mk'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="nama_mata_kuliah">Nama Mata Kuliah:</label>
                <input type="text" id="nama_mata_kuliah" name="nama_mata_kuliah" value="<?php echo htmlspecialchars($mata_kuliah['nama_mata_kuliah'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="sks">SKS:</label>
                <input type="number" id="sks" name="sks" value="<?php echo htmlspecialchars($mata_kuliah['sks'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="nip">NIP Dosen:</label>
                <select id="nip" name="nip">
                    <option value="">Pilih Dosen</option>
                    <?php
                    $dosen_query = "SELECT nip, nama FROM Dosen";
                    $dosen_result = $conn->query($dosen_query);
                    while ($dosen = $dosen_result->fetch_assoc()) {
                        echo "<option value='".$dosen['nip']."'".(isset($mata_kuliah['nip']) && $mata_kuliah['nip'] == $dosen['nip'] ? ' selected' : '').">".$dosen['nip']." - ".$dosen['nama']."</option>";
                    }
                    ?>
                </select>
            </div>
            <?php if ($mata_kuliah): ?>
                <input type="hidden" name="action" value="update">
            <?php else: ?>
                <input type="hidden" name="action" value="add">
            <?php endif; ?>
            <div class="form-group">
                <button type="submit"><?php echo $mata_kuliah ? 'Update' : 'Tambah'; ?></button>
            </div>
        </form>

        <h2 align="center">Daftar Mata Kuliah</h2>
        <?php
        $sql = "SELECT * FROM Mata_Kuliah";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Kode MK</th><th>Nama Mata Kuliah</th><th>SKS</th><th>NIP Dosen</th><th>Nama Dosen</th><th>Aksi</th></tr>";
            while ($row = $result->fetch_assoc()) {
                // Mengambil nama dosen
                $dosen_query = "SELECT nama FROM Dosen WHERE nip = ?";
                $dosen_stmt = $conn->prepare($dosen_query);
                $dosen_stmt->bind_param("s", $row["nip"]);
                $dosen_stmt->execute();
                $dosen_result = $dosen_stmt->get_result();
                $dosen_name = $dosen_result->fetch_assoc()['nama'];
                $dosen_stmt->close();

                echo "<tr>
                    <td>".$row["kode_mk"]."</td>
                    <td>".$row["nama_mata_kuliah"]."</td>
                    <td>".$row["sks"]."</td>
                    <td>".$row["nip"]."</td>
                    <td>".$dosen_name."</td>
                    <td>
                        <a href='?edit=1&kode_mk=".$row["kode_mk"]."'>Edit</a> | 
                        <a href='?delete=1&kode_mk=".$row["kode_mk"]."' onclick='return confirm(\"Anda yakin ingin menghapus mata kuliah ini?\");'>Delete</a>
                    </td>
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
