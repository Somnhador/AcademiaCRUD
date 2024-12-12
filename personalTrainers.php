<?php
session_start();
$tipoDeConta = isset($_SESSION['tipoDeConta']) ? $_SESSION['tipoDeConta'] : null;

/* Destrói a sessão após a pessoa apertar em sair da conta */
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.php");
    exit();
}
try {
    require 'conexao.php';
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Falha na conexão: " . $e->getMessage());
}

function cadastrarGenFuncPers($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cadastrar'])) {
        $nomeCompletoPF = htmlspecialchars($_POST['nomeCompletoPF']);
        $idade = $_POST['idade'];
        $genero = $_POST['genero'];
        $cpf = $_POST['cpf'];
        $formacao = $_POST['formacao'];
        $conta = $_POST['conta'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $tipoDeConta = $_POST['tipoDeConta'];

        if (strlen($conta) > 25 || strlen($cpf) > 11) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Apelido de conta ou CPF muito grande!</div>";
            header("Location: personalTrainers.php");
            exit;
        }

        $stmt = $conn->prepare("SELECT cpf, conta FROM loginCadastro WHERE cpf = :cpf OR conta = :conta");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':conta', $conta);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Funcionário já cadastrado!</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO loginCadastro (nomeCompletoPF, idade, genero, cpf, formacao, conta, senha, tipoDeConta) VALUES (:nomeCompletoPF, :idade, :genero, :cpf, :formacao, :conta, :senha, :tipoDeConta)");
            $stmt->bindParam(':nomeCompletoPF', $nomeCompletoPF);
            $stmt->bindParam(':idade', $idade);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':formacao', $formacao);
            $stmt->bindParam(':conta', $conta);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':tipoDeConta', $tipoDeConta);

            if ($stmt->execute()) {
                $_SESSION['mensagem'] = "<div class='alert alert-success text-center'>Funcionário cadastrado com sucesso!</div>";
            } else {
                $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Erro ao cadastrar funcionário!</div>";
            }
        }
        header("Location: personalTrainers.php");
        exit;
    }
}

function editarGenFuncPers($conn)
{
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar'])) {
        $id = $_POST['id'];
        $nomeCompletoPF = htmlspecialchars($_POST['nomeCompletoPF']);
        $idade = $_POST['idade'];
        $genero = $_POST['genero'];
        $cpf = $_POST['cpf'];
        $formacao = $_POST['formacao'];
        $conta = $_POST['conta'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        $tipoDeConta = $_POST['tipoDeConta'];

        if (strlen($conta) > 25 || strlen($cpf) > 11) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Apelido de conta ou CPF muito grande!</div>";
            header("Location: personalTrainers.php");
            exit;
        }

        $stmt = $conn->prepare("SELECT cpf, conta FROM loginCadastro WHERE (cpf = :cpf OR conta = :conta) AND id != :id");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':conta', $conta);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Funcionário já cadastrado!</div>";
        } else {
            $stmt = $conn->prepare("UPDATE loginCadastro SET nomeCompletoPF = :nomeCompletoPF, idade = :idade, genero = :genero, cpf = :cpf, formacao = :formacao, conta = :conta, senha = :senha, tipoDeConta = :tipoDeConta WHERE id = :id");
            $stmt->bindParam(':nomeCompletoPF', $nomeCompletoPF);
            $stmt->bindParam(':idade', $idade);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':formacao', $formacao);
            $stmt->bindParam(':conta', $conta);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':tipoDeConta', $tipoDeConta);
            $stmt->bindParam(':id', $id);

            if ($stmt->execute()) {
                $_SESSION['mensagem'] = "<div class='alert alert-success text-center'>Os dados do funcionário foram editados com sucesso!</div>";
            } else {
                $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Erro ao editar dados do funcionário!</div>";
            }
        }
        header("Location: personalTrainers.php");
        exit;
    }
}

function excluirGenFuncPers($conn)
{
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM loginCadastro WHERE id = :id");
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {

            $_SESSION['mensagem'] = "<div class='alert alert-success text-center'>Dados do funcionário deletado com sucesso!</div>";
        } else {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Erro ao deletar dados do funcionário!</div>";
        }
        header("Location: personalTrainers.php");
        exit;
    }
}

cadastrarGenFuncPers($conn);
editarGenFuncPers($conn);
excluirGenFuncPers($conn);

$searchTerm = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';
$searchWildcard = "%$searchTerm%";

$query = "SELECT * FROM loginCadastro WHERE nomeCompletoPF LIKE :searchTerm OR cpf LIKE :searchTerm";
$stmt = $conn->prepare($query);

$stmt->bindParam(':searchTerm', $searchWildcard, PDO::PARAM_STR);
$stmt->execute();

$funcionario = $stmt->fetchAll(PDO::FETCH_ASSOC);
$conn = null;
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <title>DarkSide Academia - Gerenciar Personal Trainers</title>
    <link rel="icon" type="image/x-icon" href="icone.png">
    <div id="navbar"></div>

    <style>
        body {
            background: url('acad.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            min-height: 100vh;
            align-items: center;
            color: #343a40;
            background-position: center;
            display: flex;
            flex-direction: column;
            padding-top: 100px;
        }

        .container {
            flex: 1;
            padding: 20px;
        }

        #borda,
        .form-container {
            font-size: larger;
            border-radius: 10px;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.7);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .btn-sm {
            font-size: 0.8rem;
            padding: 0.25rem 0.5rem;
        }

        .btn-sm:hover {
            opacity: 0.8;
        }


        .btn-warning,
        .btn-danger {
            font-size: 1.1rem;
            padding: 0.5rem 1rem;
            width: 100%;
        }


        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #f9f9f9, #ffffff);
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .card-title {
            padding: 2rem;
            margin-bottom: 0.25rem;
            font-size: 1.6rem;
            color: #007bff;
            font-weight: bold;
        }

        .card-text {
            margin-bottom: 0.25rem;
            color: #6c757d;
            font-size: 1.1rem;
        }

        .card-body {
            padding: 0.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body .info-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
        }

        .d-flex {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        #navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 10;
        }

        footer {
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .modal-dialog {
                max-width: 100%;
                margin: 0;
            }

            .card-body {
                flex-direction: column;
                align-items: flex-start;
            }

            .card-body .info-container {
                width: 100%;
            }

            .btn-sm {
                font-size: 1rem;
                padding: 0.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container mt-5">

        <div id="borda">

            <div class="text-center mb-4">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar Personal Trainer</button>
            </div>

            <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-center" id="cadastroModalLabel">Cadastrar Personal Trainer</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nomeCompletoPF" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="nomeCompletoPF" name="nomeCompletoPF" inputmode="text" maxlength="200" required>
                                </div>
                                <div class="mb-3">
                                    <label for="idade" class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="idade" name="idade" required>
                                </div>
                                <div class="input-group mb-3">
                                    <select name="genero" class="form-select" aria-label="Default select example" required>
                                        <option selected>Selecione seu gênero...</option>
                                        <option value="Masculino">Masculino</option>
                                        <option value="Feminino">Feminino</option>
                                        <option value="Outro">Outro</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="cpf" class="form-label">CPF</label>
                                    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="11" inputmode="numeric" required oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11)"><!-- Evento JS para limitar o cpf para apenas 11 caractéres numéricos -->
                                </div>
                                <div class="mb-3">
                                    <label for="formacao" class="form-label">Formação</label>
                                    <input type="text" class="form-control" id="formacao" name="formacao" maxlength="100" required>
                                </div>
                                <div class="mb-3">
                                    <label for="conta" class="form-label">Apelido da Conta</label>
                                    <input type="text" class="form-control" id="conta" name="conta" maxlength="25" required>
                                </div>
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Senha da Conta</label>
                                    <input type="password" class="form-control" id="senha" name="senha" maxlength="255" required>
                                </div>
                                <div class="input-group mb-3">
                                    <select name="tipoDeConta" class="form-select" aria-label="Default select example" required>
                                        <option selected>Selecione a função...</option>
                                        <option value="Personal Academia">Personal Trainer de Academia</option>
                                        <option value="Personal Privado">Personal Trainer Privado</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" name="cancelarCadastro">Cancelar</button>
                                <button type="submit" name="cadastrar" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <h3 class="text-center" style="color: white;">Lista de Personais Trainers</h3><br>

            <?php
            // Exibir a mensagem se ela estiver na sessão
            if (isset($_SESSION['mensagem'])) {
                echo $_SESSION['mensagem'];
                unset($_SESSION['mensagem']); // Limpa a mensagem após exibição
            }
            ?><br>

            <form method="POST" class="mb-4">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="search" placeholder="Buscar..." value="<?php echo $searchTerm; ?>">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary w-100">Pesquisar</button>
                    </div>
                </div>
            </form>

            <div class="container mt-4">
                <div class="row">
                    <?php if (count($funcionario) > 0) : ?>
                        <?php foreach ($funcionario as $funcionario) : ?>
                            <div class="col-12 mb-3">
                                <div class="card shadow-sm card-hover" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $funcionario['id']; ?>">
                                    <?php
                                    if ($funcionario['tipoDeConta'] == 'Personal Academia' || $funcionario['tipoDeConta'] == 'Personal Privado') {
                                    ?>
                                        <div class="card-body">
                                            <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($funcionario['nomeCompletoPF']); ?></h5>
                                            <p class="card-text"><strong>CPF: </strong> <?php echo $funcionario['cpf']; ?></p>
                                            <p class="card-text"><strong>Função: </strong> <?php echo $funcionario['tipoDeConta']; ?></p>
                                            <div class="d-flex">
                                                <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $funcionario['id']; ?>">Editar</button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $funcionario['id']; ?>">Excluir</button>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="modal fade" id="editModal<?php echo $funcionario['id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center">Editar Informação do Personal Trainer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id" value="<?php echo $funcionario['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Nome do Personal Trainer</label>
                                                    <input type="text" class="form-control" name="nomeCompletoPF" value="<?php echo $funcionario['nomeCompletoPF']; ?>" inputmode="text" maxlength="200" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Data de Nascimento</label>
                                                    <input type="date" class="form-control" name="idade" value="<?php echo $funcionario['idade']; ?>" required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <select name="genero" class="form-select" aria-label="Default select example" value="<?php echo $funcionario['genero']; ?>" required>
                                                        <option selected value="<?php echo $funcionario['genero']; ?>">Gênero...</option>
                                                        <option value="Masculino">Masculino</option>
                                                        <option value="Feminino">Feminino</option>
                                                        <option value="Outro">Outro</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">CPF</label>
                                                    <input type="text" class="form-control" id="cpf" name="cpf" maxlength="11" inputmode="numeric" value="<?php echo $funcionario['cpf']; ?>" required oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11)"><!-- Evento JS para limitar o cpf para apenas 11 caractéres numéricos -->
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Formação</label>
                                                    <input type="text" class="form-control" name="formacao" value="<?php echo $funcionario['formacao']; ?>" maxlength="100" required>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <select name="tipoDeConta" class="form-select" aria-label="Default select example" required>
                                                        <option selected value="<?php echo $funcionario['tipoDeConta']; ?>">Função...</option>
                                                        <option value="Personal Academia">Personal Trainer de Academia</option>
                                                        <option value="Personal Privado">Personal Trainer Privado</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Apelido da Conta</label>
                                                    <input type="text" class="form-control" name="conta" value="<?php echo $funcionario['conta']; ?>" maxlength="25" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Senha</label>
                                                    <input type="password" class="form-control" name="senha" value="<?php echo $funcionario['senha']; ?>" maxlength="255" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" name="editar" class="btn btn-primary">Salvar</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                            <div class="modal fade" id="deleteModal<?php echo $funcionario['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-center">Confirmar Exclusão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Deseja realmente excluir os dados do Personal Trainer <strong><?php echo htmlspecialchars($funcionario['nomeCompletoPF']); ?></strong>, do CPF <strong><?php echo htmlspecialchars($funcionario['cpf']); ?></strong> ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                                            <a href="?id=<?php echo $funcionario['id']; ?>&excluir=true" class="btn btn-danger w-100">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="viewModal<?php echo $funcionario['id']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $funcionario['id']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel<?php echo $funcionario['id']; ?>">Detalhes do Personal Trainer</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nome Completo: </strong><?php echo htmlspecialchars($funcionario['nomeCompletoPF']); ?></p>
                                            <p><strong>Data de Nascimento: </strong><?php echo date($funcionario['idade']); ?></p>
                                            <p><strong>Gênero: </strong><?php echo $funcionario['genero']; ?></p>
                                            <p><strong>CPF: </strong><?php echo $funcionario['cpf']; ?></p>
                                            <p><strong>Formação: </strong><?php echo htmlspecialchars($funcionario['formacao']); ?></p>
                                            <p><strong>Apelido da Conta: </strong><?php echo htmlspecialchars($funcionario['conta']); ?></p>
                                            <p><strong>Função: </strong><?php echo $funcionario['tipoDeConta']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-12">
                            <div class="alert alert-warning text-center">Nenhum Personal Trainer encontrado.</div>
                        </div>
                    <?php endif; ?>
                </div>
                <footer>Todos os direitos reservados &copy; 2024</footer>
            </div>
        </div>
    </div>

    <script>
        fetch('navbar.php')
            .then(response => response.text())
            .then(data => {
                document.getElementById('navbar').innerHTML = data;
            })
            .catch(error => console.error('Erro ao carregar o navbar:', error));

        // Função para esconder a mensagem após 4 segundos
        if (document.querySelector('.alert')) {
            setTimeout(() => {
                document.querySelector('.alert').style.display = 'none'; // Esconde a mensagem
            }, 5000); // Tempo em milissegundos (5000 ms = 5 segundos)
        }
    </script>

</body>

</html>