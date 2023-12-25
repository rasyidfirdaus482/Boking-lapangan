<?php
// Menggunakan koneksi ke database yang sudah dibuat sebelumnya
include 'includes/db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = 'user'; // Set default role

    // Periksa apakah username sudah digunakan
    $sql_check_username = "SELECT * FROM users WHERE username = ?";
    $stmt_check_username = $conn->prepare($sql_check_username);
    $stmt_check_username->bind_param("s", $username);
    $stmt_check_username->execute();
    $result_check_username = $stmt_check_username->get_result();

    if ($result_check_username->num_rows > 0) {
        $error = "Username sudah digunakan, silakan coba dengan username lain.";
    } else {
        // Query untuk menambahkan pengguna baru ke tabel users
        $sql_insert_user = "INSERT INTO users (username, password, role) VALUES (?, ?, ?)";
        $stmt_insert_user = $conn->prepare($sql_insert_user);
        $stmt_insert_user->bind_param("sss", $username, $password, $role);

        // Jalankan query
        if ($stmt_insert_user->execute()) {
            $success_message = "Pendaftaran berhasil! Silakan login.";
        } else {
            $error = "Terjadi kesalahan saat mendaftar pengguna.";
        }
    }
}

// Tutup koneksi ke basis data
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrasi Pengguna</title>
    <!-- Masukkan CSS atau styling jika diperlukan -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
    <div class="register">
    <h2>Registrasi Pengguna</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <button type="submit">Daftar</button>
    </form>
    </div>

    <?php
    if (isset($error)) {
        echo "<p>$error</p>";
    } elseif (isset($success_message)) {
        echo "<p>$success_message</p>";
        header("Location: halamanuser.php");
        exit();
        
    }
    ?>
</body>
</html>
