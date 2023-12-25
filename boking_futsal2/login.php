<?php
session_start();

// Menggunakan koneksi ke database yang sudah dibuat sebelumnya
include 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query untuk memeriksa keberadaan pengguna
    $sql = "SELECT * FROM users WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Pengguna ditemukan
        $row = $result->fetch_assoc();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $row['role'];
        $_SESSION['user_id'] = $row['id']; // Atur nilai 'user_id' sesuai ID pengguna
       

        // Memeriksa apakah role dari user adalah admin
if ($_SESSION['role'] === 'admin') {
    header("Location: halamanadmin.php"); // Jika role adalah admin, alihkan ke halamanadmin.php
    exit();

        } else {
            header("Location: halamanuser.php"); // Ganti dengan halaman pengguna
            exit();
        }
    } else {
        $error = "Username atau password salah.";
    }
}

// Tutup koneksi ke basis data
$conn->close();
