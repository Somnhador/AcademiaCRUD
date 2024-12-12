<?php
session_start();

// Verificar se o CPF está salvo na sessão
if (!isset($_SESSION['aluno_cpf'])) {
    header("Location: home.php"); // Redireciona se acessar diretamente
    exit();
}

try {
    require 'conexao.php';
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar informações do aluno no banco de dados
    $cpf = $_SESSION['aluno_cpf'];
    $stmt = $conn->prepare("SELECT * FROM areaAluno WHERE cpf = :cpf");
    $stmt->bindParam(':cpf', $cpf);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $aluno = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        die("Erro: Aluno não encontrado.");
    }
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DarkSide Academia - Informação do Aluno</title>
    <link rel="icon" type="image/x-icon" href="icone.png">
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
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            position: relative;
            color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            max-width: 1200px;
            /* Aumentando a largura máxima */
            width: 100%;
        }

        footer {
            text-align: center;
            color: white;
            margin-top: 20px;
        }

        .card {
            margin: 15px 0;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .card-header {
            color: #fff;
            font-weight: bold;
            text-align: center;
        }

        .card-body {
            padding: 15px;
            color: #fff;
        }

        .card-text {
            font-size: 14px;
        }

        .card-img-top {
            max-height: 200px;
            object-fit: cover;
        }

        .row {
            margin-top: 20px;
        }

        /* Definindo cores por nível de experiência */
        .card-iniciante {
            border-left: 5px solid green;
            background-color: #28a745;
        }

        .card-intermediario {
            border-left: 5px solid blue;
            background-color: #007bff;
        }

        .card-avancado {
            border-left: 5px solid yellow;
            background-color: #ffc107;
        }

        .card-atleta {
            border-left: 5px solid orange;
            background-color: #fd7e14;
        }

        .card-profissional {
            border-left: 5px solid red;
            background-color: #dc3545;
        }

        .ficha-aluno {
            text-align: center;
            margin-top: 20px;
        }

        .ficha-aluno p {
            font-size: 18px;
            margin: 10px 0;
        }

        .ficha-aluno strong {
            color: #f1c40f;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .card {
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center mb-4">Ficha do Aluno</h1>

        <div class="ficha-aluno">
            <p><strong>Nome Completo: </strong><?php echo htmlspecialchars($aluno['nomeCompletoAluno']); ?></p>
            <p><strong>Data de Nascimento: </strong><?php echo date($aluno['idade']); ?></p>
            <p><strong>Gênero: </strong><?php echo $aluno['genero']; ?></p>
            <p><strong>CPF: </strong><?php echo $aluno['cpf']; ?></p>
            <p><strong>Experiência com Academia: </strong><?php echo htmlspecialchars($aluno['experiencia']); ?></p>
            <p><strong>Assistência: </strong><?php echo htmlspecialchars($aluno['assistencia']); ?></p>
            <p><strong>Deficiência: </strong><?php echo $aluno['deficiencia']; ?></p>
        </div>

        <h3 class="mt-5 text-center">Treinos Baseados na Experiência</h3>

        <div class="row">
            <?php
            // A partir do nível de experiência, mostramos os cards com treinos
            $experiencia = $aluno['experiencia'];

            // Definindo treinos fictícios para cada nível de experiência
            $treinos = [
                'Iniciante' => [
                    ['titulo' => 'Treino de Corpo Completo', 'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia.', 'icone' => 'fa-dumbbell', 'class' => 'card-iniciante'],
                    ['titulo' => 'Treino Superior', 'descricao' => 'Aenean vestibulum urna at orci tincidunt, non varius lacus scelerisque.', 'icone' => 'fa-person-running', 'class' => 'card-iniciante'],
                    ['titulo' => 'Treino Inferior', 'descricao' => 'Sed auctor nunc eu sapien sollicitudin, nec viverra ligula venenatis.', 'icone' => 'fa-bolt', 'class' => 'card-iniciante']
                ],
                'Intermediário' => [
                    ['titulo' => 'Treino de Corpo Completo', 'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia.', 'icone' => 'fa-dumbbell', 'class' => 'card-intermediario'],
                    ['titulo' => 'Treino Superior', 'descricao' => 'Aenean vestibulum urna at orci tincidunt, non varius lacus scelerisque.', 'icone' => 'fa-person-running', 'class' => 'card-intermediario'],
                    ['titulo' => 'Treino Inferior', 'descricao' => 'Sed auctor nunc eu sapien sollicitudin, nec viverra ligula venenatis.', 'icone' => 'fa-bolt', 'class' => 'card-intermediario']
                ],
                'Avançado' => [
                    ['titulo' => 'Treino de Corpo Completo', 'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia.', 'icone' => 'fa-dumbbell', 'class' => 'card-avancado'],
                    ['titulo' => 'Treino Superior', 'descricao' => 'Aenean vestibulum urna at orci tincidunt, non varius lacus scelerisque.', 'icone' => 'fa-person-running', 'class' => 'card-avancado'],
                    ['titulo' => 'Treino Inferior', 'descricao' => 'Sed auctor nunc eu sapien sollicitudin, nec viverra ligula venenatis.', 'icone' => 'fa-bolt', 'class' => 'card-avancado']
                ],
                'Atleta' => [
                    ['titulo' => 'Treino de Corpo Completo', 'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia.', 'icone' => 'fa-dumbbell', 'class' => 'card-atleta'],
                    ['titulo' => 'Treino Superior', 'descricao' => 'Aenean vestibulum urna at orci tincidunt, non varius lacus scelerisque.', 'icone' => 'fa-person-running', 'class' => 'card-atleta'],
                    ['titulo' => 'Treino Inferior', 'descricao' => 'Sed auctor nunc eu sapien sollicitudin, nec viverra ligula venenatis.', 'icone' => 'fa-bolt', 'class' => 'card-atleta']
                ],
                'Profissional' => [
                    ['titulo' => 'Treino de Corpo Completo', 'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus lacinia.', 'icone' => 'fa-dumbbell', 'class' => 'card-profissional'],
                    ['titulo' => 'Treino Superior', 'descricao' => 'Aenean vestibulum urna at orci tincidunt, non varius lacus scelerisque.', 'icone' => 'fa-person-running', 'class' => 'card-profissional'],
                    ['titulo' => 'Treino Inferior', 'descricao' => 'Sed auctor nunc eu sapien sollicitudin, nec viverra ligula venenatis.', 'icone' => 'fa-bolt', 'class' => 'card-profissional']
                ]
            ];

            // Exibe os 3 cards para o nível de experiência
            if (array_key_exists($experiencia, $treinos)) {
                foreach ($treinos[$experiencia] as $treino) {
                    echo '<div class="col-md-4 col-sm-6 col-12">';
                    echo '<div class="card ' . $treino['class'] . '" style="width: 100%;">';
                    echo '<div class="card-header">';
                    echo '<i class="fas ' . $treino['icone'] . '"></i> ' . $treino['titulo'];
                    echo '</div>';
                    echo '<img src="foto1.png" class="card-img-top" alt="Imagem do treino">';
                    echo '<div class="card-body">';
                    echo '<p class="card-text">' . $treino['descricao'] . '</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>Não há treino disponível para o seu nível de experiência.</p>';
            }
            ?>
        </div>

        <footer>Todos os direitos reservados &copy; 2024</footer>
    </div>
</body>

</html>