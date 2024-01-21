<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fordyzx Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) {
            $email = $_POST["email"];
            $password = $_POST["password"];
            require_once "database.php";
            $sql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
            if ($user) {
                if (password_verify($password, $user["password"])) {
                    header("Location: success.php");
                    die();
                } else {
                    echo "</div class='alert alert-danger'>O Email ou a Senha está incorreto!</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>O Email ou a Senha está incorreto!</div>";
            }
        }
        ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <h1>Login</h1>
                <br><br>
                <input type="email" placeholder="Insira seu Email" name="email" class="form-control" required>
            </div>
            <div clas="form-group">
                <input type="password" placeholder="Insira sua Senha" name="password" class="form-control" required>
            </div>
            <br>
            <p>Não tem uma conta?<a href="/registration.php">Criar</a></p>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-success">
            </div>
        </form>
    </div>
</body>
</html>
