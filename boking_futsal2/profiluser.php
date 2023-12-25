<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect jika pengguna belum login
    exit();
  }
  
  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    // Hapus semua data sesi
    session_unset();
    session_destroy();
    header("Location: index.php"); // Redirect ke halaman utama setelah logout
    exit();
  }
  
  include 'includes/db_connection.php'; 
// ... (koneksi ke database dan validasi sesi)

$user_id = $_SESSION['user_id']; // Ambil ID pengguna dari sesi

// Query untuk mengambil harga per jam dari tabel lapangan (misalnya, menggunakan ID lapangan tertentu)
$id_lapangan = 1; // Ganti dengan ID lapangan yang sesuai
$sql_get_harga_per_jam = "SELECT harga_per_jam FROM lapangan WHERE id = ?";
$stmt_get_harga_per_jam = $conn->prepare($sql_get_harga_per_jam);
$stmt_get_harga_per_jam->bind_param("i", $id_lapangan);
$stmt_get_harga_per_jam->execute();
$result_harga_per_jam = $stmt_get_harga_per_jam->get_result();

if ($result_harga_per_jam->num_rows == 1) {
    $row_harga_per_jam = $result_harga_per_jam->fetch_assoc();
    $harga_per_jam = $row_harga_per_jam['harga_per_jam']; // Ambil nilai harga per jam dari database
} else {
    // Tindakan jika harga per jam tidak ditemukan
    $harga_per_jam = 0; // Atur nilai default jika tidak ditemukan
}
$stmt_get_harga_per_jam->close();

// Query untuk mendapatkan pesanan pengguna
$sql_get_user_bookings = "SELECT * FROM bookingan WHERE user_id = ?";
$stmt_get_user_bookings = $conn->prepare($sql_get_user_bookings);
$stmt_get_user_bookings->bind_param("i", $user_id);
$stmt_get_user_bookings->execute();
$result_user_bookings = $stmt_get_user_bookings->get_result();

if ($result_user_bookings === false) {
    echo "Terjadi kesalahan saat mengambil data pemesanan.";
    // Tambahkan penanganan kesalahan sesuai kebutuhan aplikasi Anda
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pesanan Saya</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Pesanan Saya</h1>

    <?php if ($result_user_bookings->num_rows > 0): ?>
        <table border="1">
        <!-- Tampilkan header tabel -->
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Jenis Lapangan</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Aksi</th> <!-- Kolom untuk tombol aksi -->
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result_user_bookings->fetch_assoc()): ?>
                <tr>
                    <!-- Tampilkan data pesanan -->
                    <td><?php echo $row['tanggal']; ?></td>
                    <td><?php echo $row['jam_mulai']; ?></td>
                    <td><?php echo $row['jam_selesai']; ?></td>
                    <td><?php echo $row['jenis_lapangan']; ?></td>
                    <td>
                            <?php
                            $jam_mulai = strtotime($row['jam_mulai']);
                            $jam_selesai = strtotime($row['jam_selesai']);
                            $durasi = ($jam_selesai - $jam_mulai) / (60 * 60); // Dalam jam
                         // Harga per jam

                            $total_harga = $durasi * $harga_per_jam;
                            echo 'Rp ' . number_format($total_harga, 0, ',', '.');
                            ?>
                        </td>
                    <td><?php echo $row['status']; ?></td>
                    <!-- Tambahkan kolom untuk tombol aksi -->
                    <td>
                        <a href="cancel_booking.php?id=<?php echo $row['id']; ?>">Batalkan</a>
                        <a href="edit_booking.php?id=<?php echo $row['id']; ?>">Edit</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Anda belum melakukan pemesanan.</p>
<?php endif; ?>

    <a href="halamanuser.php">Kembali ke Halaman Utama</a>

    <script src="js/script.js"></script>
</body>
</html>
