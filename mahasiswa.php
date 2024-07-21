<?php
include 'koneksi.php';

// Tambah Mahasiswa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nim = $_POST['nim'];
    $nama_mahasiswa = $_POST['nama_mahasiswa'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO Mahasiswa (nim, nama_mahasiswa, jenis_kelamin, no_telp, alamat)
            VALUES ('$nim', '$nama_mahasiswa', '$jenis_kelamin', '$no_telp', '$alamat')";

    if ($conn->query($sql) === TRUE) {
        echo "Mahasiswa berhasil ditambahkan";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Update Mahasiswa
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update') {
    $nim = $_POST['nim'];
    $nama_mahasiswa = $_POST['nama_mahasiswa'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $no_telp = $_POST['no_telp'];
    $alamat = $_POST['alamat'];

    $sql = "UPDATE Mahasiswa SET nama_mahasiswa='$nama_mahasiswa', jenis_kelamin='$jenis_kelamin', no_telp='$no_telp', alamat='$alamat' WHERE nim='$nim'";

    if ($conn->query($sql) === TRUE) {
        echo "Mahasiswa berhasil diupdate";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Hapus Mahasiswa
if (isset($_GET['delete'])) {
    $nim = $_GET['nim'];

    $sql = "DELETE FROM Mahasiswa WHERE nim='$nim'";

    if ($conn->query($sql) === TRUE) {
        echo "Mahasiswa berhasil dihapus";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Mendapatkan data Mahasiswa untuk diupdate
$mahasiswa = null;
if (isset($_GET['edit'])) {
    $nim = $_GET['nim'];

    $sql = "SELECT * FROM Mahasiswa WHERE nim='$nim'";
    $result = $conn->query($sql);
    $mahasiswa = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Akademik</title>
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
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
	<h2 align="center">Manajemen Mahasiswa</h2>
        <?php if ($mahasiswa): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="nim" value="<?php echo $mahasiswa['nim']; ?>">
        <?php else: ?>
            <input type="hidden" name="action" value="add">
        <?php endif; ?>
        NIM: <input type="text" name="nim" value="<?php echo $mahasiswa['nim'] ?? ''; ?>" <?php if ($mahasiswa) echo 'readonly'; ?>><br>
        Nama Mahasiswa: <input type="text" name="nama_mahasiswa" value="<?php echo $mahasiswa['nama_mahasiswa'] ?? ''; ?>"><br>
        Jenis Kelamin: 
        <select name="jenis_kelamin">
            <option value="L" <?php if (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] == 'L') echo 'selected'; ?>>Laki-Laki</option>
            <option value="P" <?php if (isset($mahasiswa['jenis_kelamin']) && $mahasiswa['jenis_kelamin'] == 'P') echo 'selected'; ?>>Perempuan</option>
        </select><br>
        No Telepon: <input type="text" name="no_telp" value="<?php echo $mahasiswa['no_telp'] ?? ''; ?>"><br>
        Alamat: <textarea name="alamat"><?php echo $mahasiswa['alamat'] ?? ''; ?></textarea><br>
        <input type="submit" value="<?php echo $mahasiswa ? 'Update' : 'Tambah'; ?>">
    </form>

    <h2 align="center">Daftar Mahasiswa</h2>
    <?php
    $sql = "SELECT * FROM Mahasiswa";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table border='1'><tr><th>NIM</th><th>Nama Mahasiswa</th><th>Jenis Kelamin</th><th>No Telepon</th><th>Alamat</th><th>Aksi</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>".$row["nim"]."</td>
                <td>".$row["nama_mahasiswa"]."</td>
                <td>".$row["jenis_kelamin"]."</td>
                <td>".$row["no_telp"]."</td>
                <td>".$row["alamat"]."</td>
                <td>
                    <a href='?edit=1&nim=".$row["nim"]."'>Edit</a> | 
                    <a href='?delete=1&nim=".$row["nim"]."'>Delete</a>
                </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "Tidak ada data mahasiswa.";
    }
    ?>
</body>
</html>
