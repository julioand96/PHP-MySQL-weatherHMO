<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="login-container">
        <form action="login.php" method="post">
            <h2>Iniciar Sesión</h2>
            <?php if (isset($_GET['error'])) { ?>
                <p class="error"><?php echo $_GET['error']; ?></p>
            <?php } ?>
            <label>Usuario</label>
            <input type="text" name="uname" placeholder="Nombre de usuario"><br>

            <label>Contraseña</label>
            <input type="password" name="password" placeholder="Contraseña"><br>

            <button type="submit">Iniciar sesión</button>
        </form>
    </div>
</body>
</html>
