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

include 'includes/db_connection.php'; // Sertakan file koneksi database

// Hapus otomatis bookingan yang sudah lewat dari database
$currentDate = date('Y-m-d');
$sql_delete_old_bookings = "DELETE FROM bookingan WHERE tanggal < ?";
$stmt_delete_old_bookings = $conn->prepare($sql_delete_old_bookings);
$stmt_delete_old_bookings->bind_param("s", $currentDate);
$stmt_delete_old_bookings->execute();
$stmt_delete_old_bookings->close();

// Query untuk mendapatkan informasi lapangan pada tanggal hari ini
$sql_get_today_bookings = "SELECT bookingan.*, users.username AS nama_pemesan
                           FROM bookingan
                           INNER JOIN users ON bookingan.user_id = users.id
                           WHERE tanggal = ?";
$stmt_get_today_bookings = $conn->prepare($sql_get_today_bookings);
$stmt_get_today_bookings->bind_param("s", $currentDate);
$stmt_get_today_bookings->execute();
$result_today_bookings = $stmt_get_today_bookings->get_result();

if ($result_today_bookings === false) {
    echo "Terjadi kesalahan saat mengambil data pemesanan.";
    // Tambahkan penanganan kesalahan sesuai kebutuhan aplikasi Anda
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Lapangan Futsal</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Sesuaikan dengan lokasi berkas CSS Anda -->
</head>
<body>

  <header>
    <nav>

      <li>Salam Olahraga, <?php echo $_SESSION['username']; ?></li>

      <li><a href="profiluser.php">Profil</a>
    </li>
    </div>
      <li><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="submit" name="logout" value="Logout">
    </form></li>
    </nav>
  </header>
    
    <!-- Konten dashboard lainnya -->

    
    <div class="content">
    <h1>Informasi Lapangan Hari Ini</h1>
    <div id="lapangan-info">
        <?php if ($result_today_bookings->num_rows > 0): ?>
            <table border="1">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Jenis Lapangan</th>
                        <th>Nama Pemesan</th>
                        
                        <!-- Tambahkan kolom lain jika diperlukan -->
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result_today_bookings->fetch_assoc()): ?>
                        <tr>
                            <td><?php
// Fungsi untuk mengubah nama hari dalam bahasa Inggris menjadi bahasa Indonesia
if (!function_exists('hari_indo')) {
  function hari_indo($hari) {
      $nama_hari = array(
          'Sun' => 'Minggu',
          'Mon' => 'Senin',
          'Tue' => 'Selasa',
          'Wed' => 'Rabu',
          'Thu' => 'Kamis',
          'Fri' => 'Jumat',
          'Sat' => 'Sabtu'
      );
      return $nama_hari[$hari];
  }
}

// Misalkan $row['tanggal'] mengandung tanggal dari database
$tanggal = $row['tanggal'];

// Ubah format tanggal menjadi d-m-Y
$tanggal_baru = date_format(date_create($tanggal), 'd-m-Y');

// Ambil nama hari dari tanggal dengan format D
$nama_hari = date('D', strtotime($tanggal));

// Ubah nama hari ke bahasa Indonesia
$nama_hari_indo = hari_indo($nama_hari);

// Tampilkan nama hari dan tanggal baru di dalam tag <>
echo "$nama_hari_indo/$tanggal_baru";

?>
</td>
                            <td><?php echo $row['jam_mulai']; ?></td>
                            <td><?php echo $row['jam_selesai']; ?></td>
                            <td><?php echo $row['jenis_lapangan']; ?></td>
                            <td><?php echo $row['nama_pemesan']; ?></td>
                           
                            <!-- Tambahkan baris data lain sesuai dengan kolom yang Anda miliki -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada data pemesanan lapangan.</p>
        <?php endif; ?>
    </div>

    <h2>Pesan Lapangan</h2>
    <?php if (isset($_SESSION['username'])): ?>
        <form action="process_booking.php" method="post">
            <input type="hidden" name="username" value="<?php echo $_SESSION['username']; ?>">
            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" required><br><br>

            <label for="jam-mulai">Jam Mulai:</label>
            <input type="time" id="jam-mulai" name="jam_mulai" required><br><br>

            <label for="jam-selesai">Jam Selesai:</label>
            <input type="time" id="jam-selesai" name="jam_selesai" required><br><br>

            <label for="jenis-lapangan">Jenis Lapangan:</label>
            <select id="jenis-lapangan" name="jenis_lapangan" required>
                <option value="A">Lapangan A</option>
                <option value="B">Lapangan B</option>
            </select><br><br>

            <button type="submit">Pesan Lapangan</button>
        </form>
    <?php else: ?>
        <p>Silakan login untuk melakukan pemesanan.</p>
    <?php endif; ?>
    </div>

    <script src="js/script.js"></script> <!-- Sesuaikan dengan lokasi berkas JavaScript Anda -->
</body>
</html>
