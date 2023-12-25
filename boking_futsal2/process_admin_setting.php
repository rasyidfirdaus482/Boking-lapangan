<?php
include 'includes/db_connection.php'; // Sertakan file koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $harga_per_jam = $_POST['harga_per_jam'];

    
    // Query untuk menyimpan harga per jam ke dalam database
    $sql_update_harga = "UPDATE lapangan SET harga_per_jam = ? WHERE id = ?";
    $stmt_update_harga = $conn->prepare($sql_update_harga);
    $stmt_update_harga->bind_param("ii", $harga_per_jam, $lapangan_id);
    $stmt_update_harga->execute();
    $stmt_update_harga->close();
    

    header("Location: admin_setting.php"); // Redirect kembali ke halaman admin setting
    exit();
}
?>
