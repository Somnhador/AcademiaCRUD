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
        $nomeCompletoAluno = htmlspecialchars($_POST['nomeCompletoAluno']);
        $idade = $_POST['idade'];
        $genero = $_POST['genero'];
        $cpf = $_POST['cpf'];
        $experiencia = $_POST['experiencia'];
        $assistencia = $_POST['assistencia'];
        $deficiencia = $_POST['deficiencia'];

        if (strlen($cpf) > 11) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>CPF muito grande!</div>";
            header("Location: alunos.php");
            exit;
        }

        $stmt = $conn->prepare("SELECT cpf FROM areaAluno WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Aluno já cadastrado!</div>";
        } else {
            $stmt = $conn->prepare("INSERT INTO areaAluno (nomeCompletoAluno, idade, genero, cpf, experiencia, assistencia, deficiencia) VALUES (:nomeCompletoAluno, :idade, :genero, :cpf, :experiencia, :assistencia, :deficiencia)");
            $stmt->bindParam(':nomeCompletoAluno', $nomeCompletoAluno);
            $stmt->bindParam(':idade', $idade);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':experiencia', $experiencia);
            $stmt->bindParam(':assistencia', $assistencia);
            $stmt->bindParam(':deficiencia', $deficiencia);

            if ($stmt->execute()) {
                $_SESSION['mensagem'] = "<div class='alert alert-success text-center'>Aluno cadastrado com sucesso!</div>";
            } else {
                $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Erro ao cadastrar aluno!</div>";
            }
        }
        header("Location: alunos.php");
        exit;
    }
}

function editarGenFuncPers($pdo)
{
    if (isset($_POST['editar'])) {
        $id = $_POST['id_aluno'];
        $nome = $_POST['nomeCompletoAluno'];
        $idade = $_POST['idade'];
        $genero = $_POST['genero'];
        $cpf = $_POST['cpf'];
        $experiencia = $_POST['experiencia'];
        $assistencia = $_POST['assistencia'];
        $deficiencia = $_POST['deficiencia'];

        try {
            // Query corrigida
            $sql = "UPDATE areaAluno SET 
                    nomeCompletoAluno = :nome, 
                    idade = :idade, 
                    genero = :genero, 
                    cpf = :cpf, 
                    experiencia = :experiencia, 
                    assistencia = :assistencia, 
                    deficiencia = :deficiencia 
                    WHERE id_aluno = :id";

            $stmt = $pdo->prepare($sql);

            // Bind dos valores
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':idade', $idade);
            $stmt->bindParam(':genero', $genero);
            $stmt->bindParam(':cpf', $cpf);
            $stmt->bindParam(':experiencia', $experiencia);
            $stmt->bindParam(':assistencia', $assistencia);
            $stmt->bindParam(':deficiencia', $deficiencia);
            $stmt->bindParam(':id', $id);

            // Executa a query
            $stmt->execute();

            $_SESSION['mensagem'] = '<div class="alert alert-success">Dados atualizados com sucesso!</div>';
        } catch (PDOException $e) {
            $_SESSION['mensagem'] = '<div class="alert alert-danger">Erro: ' . $e->getMessage() . '</div>';
        }
        header("Location: alunos.php");
        exit;
    }
}

function excluirGenFuncPers($conn)
{
    if (isset($_GET['id_aluno'])) {
        $id_aluno = $_GET['id_aluno'];

        $stmt = $conn->prepare("DELETE FROM areaAluno WHERE id_aluno = :id_aluno");
        $stmt->bindParam(':id_aluno', $id_aluno);

        if ($stmt->execute()) {

            $_SESSION['mensagem'] = "<div class='alert alert-success text-center'>Dados do Aluno deletado com sucesso!</div>";
        } else {
            $_SESSION['mensagem'] = "<div class='alert alert-danger text-center'>Erro ao deletar dados do Aluno!</div>";
        }
        header("Location: alunos.php");
        exit;
    }
}

cadastrarGenFuncPers($conn);
editarGenFuncPers($conn);
excluirGenFuncPers($conn);

$searchTerm = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : '';
$searchWildcard = "%$searchTerm%";

$query = "SELECT * FROM areaAluno WHERE nomeCompletoAluno LIKE :searchTerm OR cpf LIKE :searchTerm";
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

    <title>DarkSide Academia - Gerenciar Alunos</title>
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
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#cadastroModal">Cadastrar Aluno</button>
            </div>

            <div class="modal fade" id="cadastroModal" tabindex="-1" aria-labelledby="cadastroModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title text-center" id="cadastroModalLabel">Cadastrar Aluno</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="nomeCompletoAluno" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="nomeCompletoAluno" name="nomeCompletoAluno" inputmode="text" maxlength="200" required>
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
                                <div class="input-group mb-3">
                                    <select name="experiencia" class="form-select" aria-label="Default select example" required>
                                        <option selected>Selecione sua experiência...</option>
                                        <option value="Iniciante">Iniciante</option>
                                        <option value="Intermediário">Intermediário</option>
                                        <option value="Avançado">Avançado</option>
                                        <option value="Atleta">Atleta</option>
                                        <option value="Profissional">Profissional</option>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <select name="assistencia" class="form-select" aria-label="Default select example" required>
                                        <option selected>Precisa de Assistência?</option>
                                        <option value="Sim">Sim</option>
                                        <option value="Nao">Não</option>
                                    </select>
                                </div>
                                <div class="input-group mb-3">
                                    <select name="deficiencia" class="form-select" aria-label="Default select example" required>
                                        <option selected>Deficiência?</option>
                                        <option value="Sim">Sim</option>
                                        <option value="Nao">Não</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" name="cancelarCadastro">Cancelar</button>
                                <button type="submit" name="cadastrar" class="btn btn-success">Cadastrar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <h3 class="text-center" style="color: white;">Lista de Alunos</h3><br>

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
                        <button type="submit" class="btn btn-success w-100">Pesquisar</button>
                    </div>
                </div>
            </form>

            <div class="container mt-4">
                <div class="row">
                    <?php if (count($funcionario) > 0) : ?>
                        <?php foreach ($funcionario as $funcionario) : ?>
                            <div class="col-12 mb-3">
                                <div class="card shadow-sm card-hover" data-bs-toggle="modal" data-bs-target="#viewModal<?php echo $funcionario['id_aluno']; ?>">
                                    <?php
                                    if ($funcionario['alunoDefault'] == 'Aluno') {
                                    ?>
                                        <div class="card-body">
                                            <h5 class="card-title text-primary fw-bold"><?php echo htmlspecialchars($funcionario['nomeCompletoAluno']); ?></h5>
                                            <p class="card-text"><strong>CPF: </strong> <?php echo $funcionario['cpf']; ?></p>
                                            <p class="card-text"><strong>Gênero: </strong> <?php echo $funcionario['genero']; ?></p>
                                            <div class="d-flex">
                                                <button class="btn btn-warning btn-sm mb-2" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $funcionario['id_aluno']; ?>">Editar</button>
                                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal<?php echo $funcionario['id_aluno']; ?>">Excluir</button>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="modal fade" id="editModal<?php echo $funcionario['id_aluno']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-center">Editar Informação de Aluno</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="hidden" name="id_aluno" value="<?php echo $funcionario['id_aluno']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Nome do Aluno</label>
                                                    <input type="text" class="form-control" name="nomeCompletoAluno" value="<?php echo $funcionario['nomeCompletoAluno']; ?>" inputmode="text" maxlength="200" required>
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
                                                <div class="input-group mb-3">
                                                    <select name="experiencia" class="form-select" aria-label="Default select example" required>
                                                        <option selected value="<?php echo $funcionario['experiencia']; ?>">Experiência...</option>
                                                        <option value="Iniciante">Iniciante</option>
                                                        <option value="Intermediário">Intermediário</option>
                                                        <option value="Avançado">Avançado</option>
                                                        <option value="Atleta">Atleta</option>
                                                        <option value="Profissional">Profissional</option>
                                                    </select>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <select name="assistencia" class="form-select" aria-label="Default select example" required>
                                                        <option selected value="<?php echo $funcionario['assistencia']; ?>">Assistência...</option>
                                                        <option value="Sim">Sim</option>
                                                        <option value="Nao">Não</option>
                                                    </select>
                                                </div>
                                                <div class="input-group mb-3">
                                                    <select name="deficiencia" class="form-select" aria-label="Default select example" required>
                                                        <option selected value="<?php echo $funcionario['deficiencia']; ?>">Deficiência...</option>
                                                        <option value="Sim">Sim</option>
                                                        <option value="Nao">Não</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" name="editar" class="btn btn-success">Salvar</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                            <div class="modal fade" id="deleteModal<?php echo $funcionario['id_aluno']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-center">Confirmar Exclusão</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Deseja realmente excluir os dados do Aluno <strong><?php echo htmlspecialchars($funcionario['nomeCompletoAluno']); ?></strong>, do CPF <strong><?php echo htmlspecialchars($funcionario['cpf']); ?></strong> ?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">Cancelar</button>
                                            <a href="?id_aluno=<?php echo $funcionario['id_aluno']; ?>&excluir=true" class="btn btn-danger w-100">Excluir</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="viewModal<?php echo $funcionario['id_aluno']; ?>" tabindex="-1" aria-labelledby="viewModalLabel<?php echo $funcionario['id_aluno']; ?>" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="viewModalLabel<?php echo $funcionario['id_aluno']; ?>">Detalhes do Aluno</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Nome Completo: </strong><?php echo htmlspecialchars($funcionario['nomeCompletoAluno']); ?></p>
                                            <p><strong>Data de Nascimento: </strong><?php echo date($funcionario['idade']); ?></p>
                                            <p><strong>Gênero: </strong><?php echo $funcionario['genero']; ?></p>
                                            <p><strong>CPF: </strong><?php echo $funcionario['cpf']; ?></p>
                                            <p><strong>Experiência com Academia: </strong><?php echo htmlspecialchars($funcionario['experiencia']); ?></p>
                                            <p><strong>Assistência: </strong><?php echo htmlspecialchars($funcionario['assistencia']); ?></p>
                                            <p><strong>Deficiência: </strong><?php echo $funcionario['deficiencia']; ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="col-12">
                            <div class="alert alert-warning text-center">Nenhum Aluno encontrado.</div>
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