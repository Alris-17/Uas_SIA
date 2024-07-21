<?php
include 'koneksi.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $no_telp = $_POST['no_telp'];

    if ($action == 'add') {
        $sql = "INSERT INTO Dosen (nip, nama, alamat, email, no_telp) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $nip, $nama, $alamat, $email, $no_telp);
            if ($stmt->execute()) {
                $message = "Dosen berhasil ditambahkan.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Query database gagal.";
        }
    } elseif ($action == 'update') {
        $sql = "UPDATE Dosen SET nama = ?, alamat = ?, email = ?, no_telp = ? WHERE nip = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("sssss", $nama, $alamat, $email, $no_telp, $nip);
            if ($stmt->execute()) {
                $message = "Dosen berhasil diupdate.";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Query database gagal.";
        }
    }
}

if (isset($_GET['delete'])) {
    $nip = $_GET['nip'];
    $sql = "DELETE FROM Dosen WHERE nip = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $nip);
        if ($stmt->execute()) {
            $message = "Dosen berhasil dihapus.";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Query database gagal.";
    }
}

$dosen = null;
if (isset($_GET['edit'])) {
    $nip = $_GET['nip'];
    $sql = "SELECT * FROM Dosen WHERE nip = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $nip);
        $stmt->execute();
        $result = $stmt->get_result();
        $dosen = $result->fetch_assoc();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Dosen</title>
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
        <h2 align="center">Manajemen Dosen</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="nip">NIP:</label>
                <input type="text" id="nip" name="nip" value="<?php echo $dosen['nip'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="nama">Nama:</label>
                <input type="text" id="nama" name="nama" value="<?php echo $dosen['nama'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat:</label>
                <textarea id="alamat" name="alamat" required><?php echo $dosen['alamat'] ?? ''; ?></textarea>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo $dosen['email'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label for="no_telp">No. Telepon:</label>
                <input type="text" id="no_telp" name="no_telp" value="<?php echo $dosen['no_telp'] ?? ''; ?>" required>
            </div>
            <?php if ($dosen): ?>
                <input type="hidden" name="action" value="update">
            <?php else: ?>
                <input type="hidden" name="action" value="add">
            <?php endif; ?>
            <div class="form-group">
                <button type="submit"><?php echo $dosen ? 'Update' : 'Tambah'; ?></button>
            </div>
        </form>

        <h2 align="center">Daftar Dosen</h2>
        <?php
        $sql = "SELECT * FROM Dosen";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table><tr><th>NIP</th><th>Nama</th><th>Alamat</th><th>Email</th><th>No. Telepon</th><th>Aksi</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["nip"]."</td>
                    <td>".$row["nama"]."</td>
                    <td>".$row["alamat"]."</td>
                    <td>".$row["email"]."</td>
                    <td>".$row["no_telp"]."</td>
                    <td>
                        <a href='?edit=1&nip=".$row["nip"]."'>Edit</a> | 
                        <a href='?delete=1&nip=".$row["nip"]."'>Delete</a>
                    </td>
                    </tr>";
            }
            echo "</table>";
        } else {
            echo "Tidak ada data dosen.";
        }
        ?>
    </div>
</body>
</html>
