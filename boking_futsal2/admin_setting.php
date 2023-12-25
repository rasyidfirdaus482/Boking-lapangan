<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Setting</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header style="position: relative;">
        <nav>
        </nav>
    </header>
    <h1>Admin Setting</h1>
    <form action="process_admin_setting.php" method="post">
        <label for="harga_per_jam">Harga per Jam:</label>
        <input type="number" id="harga_per_jam" name="harga_per_jam" required><br><br>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
