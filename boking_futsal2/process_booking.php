<?php
session_start();

// Menggunakan koneksi ke database yang sudah dibuat sebelumnya
include 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username']; // Mengambil username dari sesi
    $tanggal = $_POST["tanggal"];
    $jamMulai = $_POST["jam_mulai"];
    $jamSelesai = $_POST["jam_selesai"];
    $jenisLapangan = $_POST["jenis_lapangan"];

    // Mendapatkan ID pengguna berdasarkan username
    $sql_get_user_id = "SELECT id FROM users WHERE username = ?";
    $stmt_get_user_id = $conn->prepare($sql_get_user_id);
    $stmt_get_user_id->bind_param("s", $username);
    $stmt_get_user_id->execute();
    $result_get_user_id = $stmt_get_user_id->get_result();

    if ($result_get_user_id->num_rows == 1) {
        $row = $result_get_user_id->fetch_assoc();
        $userId = $row['id'];

        // Query untuk menyimpan data pemesanan ke tabel bookings
        $sql_insert_booking = "INSERT INTO bookingan (user_id, tanggal, jam_mulai, jam_selesai, jenis_lapangan) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_booking = $conn->prepare($sql_insert_booking);
        $stmt_insert_booking->bind_param("issss", $userId, $tanggal, $jamMulai, $jamSelesai, $jenisLapangan);

        // Jalankan query
        if ($stmt_insert_booking->execute()) {
            header('location:halamanuser.php');
            echo "<p>Pemesanan berhasil disimpan!</p>";
        } else {
            echo "<p>Maaf, terjadi kesalahan saat menyimpan pemesanan.</p>";
        }
    } else {
        echo "<p>Pengguna tidak ditemukan.</p>";
    }
}

// Tutup koneksi ke basis data
$conn->close();
?>
