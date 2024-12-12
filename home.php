<?php
session_start();

$tipoDeConta = isset($_SESSION['tipoDeConta']) ? $_SESSION['tipoDeConta'] : null;
try {
    require 'conexao.php';

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Falha na conexão: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $conta = $_POST['conta'];
    $senha = $_POST['senha'];

    $stmt = $conn->prepare("SELECT * FROM loginCadastro WHERE conta = :conta");
    $stmt->bindParam(':conta', $conta);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);


        if (password_verify($senha, $user['senha'])) {
            $_SESSION['tipoDeConta'] = $user['tipoDeConta'];
            header("Location: menu.php");
            exit();
        } else {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Nome de usuário ou senha incorretos!</div>";
        }
    } else {
        $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Nome de usuário ou senha incorretos!</div>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Checar se a requisição é para acessar a ficha do aluno
    if (isset($_POST['acessarFicha'])) {
        $cpf = $_POST['cpf'];

        // Validação básica do CPF
        if (strlen($cpf) !== 11) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>CPF inválido!</div>";
        } else {
            // Consulta ao banco de dados para verificar o CPF
            $stmt = $conn->prepare("SELECT * FROM areaAluno WHERE cpf = :cpf");
            $stmt->bindParam(':cpf', $cpf);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // CPF encontrado, buscar os dados do aluno
                $aluno = $stmt->fetch(PDO::FETCH_ASSOC);

                // Salva o CPF ou ID do aluno na sessão
                $_SESSION['aluno_cpf'] = $aluno['cpf'];

                // Redireciona para a página com as informações do aluno
                header("Location: infoAlunoCPF.php");
                exit();
            } else {
                // CPF não encontrado
                $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>CPF não encontrado! Verifique os dados e tente novamente.</div>";
            }
        }
    }
}

$conn = null;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DarkSide Academia</title>
    <link rel="icon" type="image/x-icon" href="icone.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: url('acad.png') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        /* Div de login */
        .card {
            position: relative;
            color: white;
            font-size: 1.2rem;
            border-radius: 10px;
            padding: 2rem;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        /* Retira linha da UL */
        .nav-tabs {
            border-bottom: none;
        }

        /* As abas ficam mais flexíveis */
        .nav-tabs .nav-link {
            flex-grow: 1;
            text-align: center;
            color: white;
        }

        /* Aba selecionada, a palavra fica branca */
        .nav-tabs .nav-link.active {
            color: white;
        }

        /* (Aba funcionário) Quando a aba é selecionada o background fica azul */
        #funcAba.nav-link.active {
            background-color: #007bff;
        }

        /* (Aba funcionário) Quando a aba não está selecionada, a palavra fica verde */
        #abas .nav-link#funcAba:not(.active) {
            color: #28a745;
        }

        /* (Aba funcionário) Aba não selecionada muda de cor quando o mouse passar em cima */
        #abas .nav-link#funcAba:not(.active):hover {
            background-color: #28a745;
            color: white;
        }

        /* (Aba aluno) Quando a aba é selecionada o background fica verde */
        #alunoAba.nav-link.active {
            background-color: #28a745;
        }

        /* (Aba aluno) Quando a aba não está selecionada, a palavra fica azul */
        #abas .nav-link#alunoAba:not(.active) {
            color: #007bff;
        }

        /* (Aba aluno) Aba não selecionada muda de cor quando o mouse passar em cima */
        #abas .nav-link#alunoAba:not(.active):hover {
            background-color: #007bff;
            color: white;
        }

        /* largura das abas */
        #funcAba,
        #alunoAba {
            width: 18.19rem;
        }

        footer {
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        /* Adapta para telas menores */
        @media (max-width: 768px) {
            .card {
                padding: 1rem;
            }

            .nav-tabs .nav-link {
                font-size: 0.9rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <h2 class="text-center mb-4">LOGIN</h2>
                <?php
                // Exibir a mensagem se ela estiver na sessão
                if (isset($_SESSION['mensagem'])) {
                    echo $_SESSION['mensagem'];
                    unset($_SESSION['mensagem']); // Limpa a mensagem após exibição
                }
                ?>

                <!-- Abas do funcionário e do aluno -->
                <ul class="nav nav-tabs" id="abas">
                    <li class="nav-item">
                        <!-- data-bs-toggle="tab" e data-bs-target="#" são JS responsáveis pela alternância das abas -->
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#login" id="funcAba">Funcionário</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#aluno" id="alunoAba">Aluno</button>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- Login do funcionário -->

                    <div class="tab-pane fade show active" id="login">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="loginNome" class="form-label">Usuário</label>
                                <input type="text" class="form-control" id="loginNome" name="conta" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginSenha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="loginSenha" name="senha" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button>
                        </form>
                    </div>
                    <!-- Acessar ficha do aluno -->
                    <div class="tab-pane fade" id="aluno">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF do Aluno</label>
                                <input type="text" class="form-control" id="cpf" name="cpf" maxlength="11" inputmode="numeric" required oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11)"><!-- Evento JS para limitar o cpf para apenas 11 caractéres numéricos -->
                                <br>
                                <button type="submit" name="acessarFicha" class="btn btn-success w-100">Acessar Ficha</button>
                        </form>
                    </div>
                </div>
            </div>
            <footer>Todos os direitos reservados &copy; 2024</footer>
        </div>
    </div>
    <script>
        // Função para esconder a mensagem após 4 segundos
        if (document.querySelector('.alert')) {
            setTimeout(() => {
                document.querySelector('.alert').style.display = 'none'; // Esconde a mensagem
            }, 5000); // Tempo em milissegundos (5000 ms = 5 segundos)
        }
    </script>
</body>

</html>