<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-Educational Harmonie</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            color: #143C5F;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
            overflow: hidden;
        }

        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            height: 300px;
            width: auto;
            object-fit: contain;
        }

        .header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #2878BD;
            color: white;
            text-align: center;
            padding: 10px 0;
            font-size: 24px;
            z-index: 1;
        }

        .background-text {
            position: absolute;
            top: 10%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 70px;
            font-weight: bold;
            color: rgba(20, 60, 95, 0.1);
            text-align: center;
            pointer-events: none;
        }

        .login-form {
            max-width: 400px;
            padding: 30px;
            border: 1px solid #2878BD;
            background-color: white;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1;
        }
        .login-form h3 {
            color: #2878BD;
            margin-bottom: 20px;
        }
        .login-form .form-control {
            border-color: #2878BD;
        }
        .login-form .btn-primary {
            background-color: #2878BD;
            border: none;
            width: 100%;
        }
        .login-form .btn-primary:hover {
            background-color: #143C5F;
        }
        .remember-me {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="header">BIENVENIDOS A EDUCATIONAL HARMONIE</div>

<img src="../static/img/logo.JPG.jpg" alt="Logo de Educational Harmonie" class="logo">

<div class="background-text"></div>

<div class="login-form">
    <div class="text-center mb-3">
        <i class="fas fa-user-circle fa-3x"></i>
    </div>
    <h3>INICIAR SESIÓN</h3>
    <form action="validar.php" method="POST">
        <div class="mb-3">
            <label>
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </label>
        </div>
        <div class="mb-3">
            <label>
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </label>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
        <div class="mt-3 remember-me">
            <input type="checkbox" id="rememberMe">
            <label for="rememberMe">Recordar contraseña</label>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



