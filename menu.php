<?php
/* Inicia uma sessão com o tipo da conta */
session_start();

$tipoDeConta = isset($_SESSION['tipoDeConta']) ? $_SESSION['tipoDeConta'] : null;

/* Destrói a sessão após a pessoa apertar em sair da conta */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DarkSide Academia - Menu</title>
    <link rel="icon" type="image/x-icon" href="icone.png">
    <!-- Chama a navbar pelo id usando uma div -->
    <div id="navbar"></div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            color: white;
            background: url('acad.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            height: 100vh;
            align-items: center;
            justify-content: center;
            color: #343a40;
            padding-top: 300px;
        }

        .container {
            position: relative;
            color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #3e3e3e;
            color: rgb(255, 255, 255);
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        /* Ajusta a navbar to topo */
        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }

        .nav-link {
            color: white;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2 class="text-center">Bem-vindo ao Sistema da Darkside Academia</h2>
        <p class="text-center">ATENÇÃO! CADA TIPO DE CONTA(CARGO) CONTÉM UM LEVEL DE PERMISSÃO DIFERENTE!</p>
    </div>

    <div class="footer">
        <footer>Todos os direitos reservados &copy; 2024</footer>
    </div>

    <!-- Chama a navbar -->
    <script>
        fetch('navbar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar').innerHTML = data;
            })
            .catch(error => console.error('Erro ao carregar o navbar:', error));
    </script>
</body>

</html>