<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" rel="stylesheet" crossorigin="anonymous">
    <script>
        function showRegisterForm() {
            document.getElementById('formContainer').innerHTML = `
                <h2 class="text-center mb-4" style="color:#347928;">Register</h2>
                <form method="POST" action="auth/register.php">
                    <div class="mb-3">
                        <label for="register_email" class="form-label">Email:</label>
                        <input type="email" name="user_email" id="register_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="register_password" class="form-label">Password:</label>
                        <input type="password" name="user_password" id="register_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_name" class="form-label">Nama:</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" required>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Register</button>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="javascript:void(0);" class="text-decoration-none"style="color:#347928;" onclick="showLoginForm()">Sudah punya akun? Kembali ke Login</a>
                    </div>
                </form>
            `;
        }

        function showLoginForm() {
            document.getElementById('formContainer').innerHTML = `
                <h2 class="text-center mb-4" style="color: #347928;">Login</h2>
                <form method="POST" action="auth/auth.php">
                    <div class="mb-3">
                        <label for="user_email" class="form-label">Email:</label>
                        <input type="email" name="user_email" id="user_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="user_password" class="form-label">Password:</label>
                        <input type="password" name="user_password" id="user_password" class="form-control" required>
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember Me</label>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn" style="background-color:#347928; color:#C0EBA6;">Login</button>
                    </div>
                    <div class="mt-3 text-center">
                        <a href="javascript:void(0);" class="text-decoration-none" style="color:#347928;" onclick="showRegisterForm()">Belum punya akun? Register di sini</a>
                    </div>
                </form>
            `;
        }

        window.onload = showLoginForm;
    </script>
</head>
<body class="bg-light">

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh; background-color: #FFFBE6;">
        <div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px; background-color: #C0EBA6; border-radius: 10px;">
            <div id="formContainer"></div>
        </div>
    </div>

</body>
</html>
