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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        .navbar {
            backdrop-filter: blur(11px);
            box-shadow: 0px 0px 30px rgba(227, 228, 237, 0.37);
            border: 2px solid rgba(255, 255, 255, 0.18);
        }

        .navbar-nav {
            display: flex;
            width: 100%;
        }

        /* Espaçamento dos itens */
        .nav-item {
            margin-left: 1.2rem;
            margin-right: 1.2rem;
        }

        /* Aumenta o tamanho da fonte e adiciona border-radius */
        .nav-link {
            color: white;
            font-size: 1.7rem !important;
            padding: 0.5rem 1rem !important;
            transition: all 0.7s ease !important;
            border-radius: 15px !important;
        }

        /* Efeito de hover nos links da navbar */
        .nav-link:hover {
            background-color: #5e5e5e;
        }

        /* Estilo para o botão de navegação em telas pequenas */
        .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.18) !important;
        }

        /* Joga o botão de sair para o canto */
        #navSair {
            margin-left: auto;
        }

        /* Adapta para telas menores */
        @media (max-width: 768px) {
            .navbar-nav {
                flex-direction: column;
                text-align: center;
            }

            .nav-item {
                margin: 0.5rem 0;
            }

            /* O botão de sair vai para o meio para caber na lista */
            #navSair {
                margin: auto;
            }

        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg" id="navs">
        <div class="container-fluid">
            <!-- Imagem linkada ao menu -->
            <a class="navbar-brand" href="menu.php">
                <img src="icone.png" alt="Home" width="70" height="70" class="d-inline-block align-text-top">
            </a>
            <!-- JS para botão em telas pequenas -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Links/itens da navbar -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <?php if ($tipoDeConta == "Gerente") : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="funcionarios.php">Gerenciar Gerentes e Funcionários</a>
                        </li>
                    <?php endif; ?>
                    <?php if ($tipoDeConta == "Funcionário" || $tipoDeConta == "Gerente") : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="personalTrainers.php">Gerenciar Personal Trainers</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link" href="alunos.php">Gerenciar Alunos</a>
                    </li>
                    <li class="nav-item" id="navSair">
                        <a class="nav-link text-danger" href="?logout=true" id="hoverSair2">
                            <i class="fas fa-sign-out-alt" id="hoverSair1"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>