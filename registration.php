<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title>Fordyzx Website</title>
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
            $fullName = $_POST["fullname"];
            $email = $_POST["email"];
            $password = $_POST["password"];
            $confirmPassword = $_POST["confirm_password"];

            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            $errors = array();

            if (empty($fullName) OR empty($email) OR empty($password) OR empty($confirmPassword)) {
                array_push($errors, "Todos os campos são obrigatórios!");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                array_push($errors, "O E-mail não é válido!");
            }
            if (strlen($password) < 8) {
                array_push($errors, "A senha deve ter pelo menos 8 caracteres.");
            }
            if ($password !== $confirmPassword) {
                array_push($errors, "A senha não corresponde!");
            }

            require_once "database.php"; // Certifique-se de que o arquivo "database.php" está correto

            // Verificar se o e-mail já existe
            $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                $rowCount = mysqli_stmt_num_rows($stmt);
                mysqli_stmt_close($stmt);

                if ($rowCount > 0) {
                    array_push($errors, "O E-mail já existe!");
                }
            } else {
                // Tratar erro na preparação da consulta
                die("Erro ao preparar a consulta: " . mysqli_error($conn));
            }

            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo "<div class='alert alert-danger'>$error</div>";
                }
            } else {
                // Inserir novo usuário
                $stmt = mysqli_prepare($conn, "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);

                    echo "<div class='alert alert-success'>Você foi registrado com sucesso.</div>";
                } else {
                    // Tratar erro na preparação da consulta
                    die("Erro ao preparar a consulta: " . mysqli_error($conn));
                }
            }
        }
        ?>
        
        <form action="registration.php" method="POST">
            <h1>Cadastrar</h1>
            <br>
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Insira o nome de Usuário" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Insira seu e-mail" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Insira sua senha" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirme sua senha" required>
            </div>
            <p>Já tem uma conta?<a href="/login.php">Entrar</a></p>
            <br>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Registrar" name="submit">
            </div>
        </form>
    </div>
</body>
</html>
