<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php"); // Redirect jika bukan admin
    exit();
}


include 'includes/db_connection.php'; // Sertakan file koneksi database

// Hapus booking jika ID booking dikirimkan melalui parameter URL 'hapus'
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['hapus'])) {
    $hapus_id = $_GET['hapus'];
    $sql_hapus_booking = "DELETE FROM bookingan WHERE id = ?";
    $stmt_hapus_booking = $conn->prepare($sql_hapus_booking);
    $stmt_hapus_booking->bind_param("i", $hapus_id);
    $stmt_hapus_booking->execute();
    $stmt_hapus_booking->close();
    header("Location: halamanadmin.php"); // Redirect kembali ke halaman admin setelah penghapusan
    exit();
}
// Cek apakah tombol logout ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Hapus semua data sesi
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect ke halaman utama setelah logout
    exit();
}


// Query untuk mendapatkan semua informasi lapangan dan booking beserta nama pengguna
$sql_get_all_bookings = "SELECT bookingan.*, users.username AS nama_pemesan
                         FROM bookingan
                         INNER JOIN users ON bookingan.user_id = users.id";
$result_all_bookings = $conn->query($sql_get_all_bookings);

if ($result_all_bookings === false) {
    echo "Terjadi kesalahan saat mengambil data pemesanan.";
    // Tambahkan penanganan kesalahan sesuai kebutuhan aplikasi Anda
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Sesuaikan dengan lokasi berkas CSS Anda -->
</head>
<body>
    <header>
        <nav>
            <li>Selamat datang, <?php echo $_SESSION['username']; ?></li>
            <li><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="logout" value="Logout">
    </form></li>
        </nav>
    </header>

    <h1>Admin Dashboard</h1>

    <h2>Daftar Booking</h2>
    <div id="lapangan-info">
        <?php if ($result_all_bookings->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Jenis Lapangan</th>
                        <th>Nama Pemesan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_all_bookings->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['tanggal']; ?></td>
                            <td><?php echo $row['jam_mulai']; ?></td>
                            <td><?php echo $row['jam_selesai']; ?></td>
                            <td><?php echo $row['jenis_lapangan']; ?></td>
                            <td><?php echo $row['nama_pemesan']; ?></td>
                            <td><?php echo $row['status']; ?></td>
                            <td>
                            <a href="verifikasi_booking.php?id=<?php echo $row['id']; ?>">Verifikasi</a>
                            <a href="halamanadmin.php?hapus=<?php echo $row['id']; ?>">Hapus</a></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button><a href="admin_setting.php">Pengaturan</a></button>
        <?php else: ?>
            <p>Tidak ada data pemesanan lapangan.</p>
        <?php endif; ?>
    </div>

    <!-- Fungsi-fungsi admin lainnya -->
    <!-- ... (tambahkan fungsi admin sesuai kebutuhan) -->

    <script src="js/script.js"></script> <!-- Sesuaikan dengan lokasi berkas JavaScript Anda -->
</body>
</html>
