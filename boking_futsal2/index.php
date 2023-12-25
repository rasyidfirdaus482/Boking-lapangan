<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- Masukkan CSS atau styling jika diperlukan -->
    <link rel="stylesheet" href="css/login.css">
    <!-- font google roboto-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,300;0,700;1,100&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login" id="login">
    <h2>Login</h2>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <p>Belum mempunyai akun?<a href="register.php">Daftar</a></p>
        <br><br>
        <button type="submit">Login</button>
       
    </form>
    </div>

    <?php
    if (isset($error)) {
        echo "<p>$error</p>";
    }
    ?>
</body>
</html>
