<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
</body>
</html>
