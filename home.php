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
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        #cardd {
            color: white;
            font-size: larger;
            border-radius: 10px;
            padding: 50px;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        #myTab .nav-link#login-tab:not(.active) {
            color: #28a745;
        }

        .nav-tabs .nav-link.active {
            color: white;
        }

        #login-tab.nav-link.active {
            background-color: #007bff;
        }

        #register-tab.nav-link.active {
            background-color: #28a745;
        }

        #login-tab,
        #register-tab {
            width: 280px;
        }


        #liberarAcesso {
            background-color: #ff9b00;
        }

        #liberarAcesso:hover {
            background-color: #d88402;
        }

        .footer {
            text-align: center;
            padding: 10px;
            background-color: #3e3e3e;
            color: rgb(255, 255, 255);
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>

<body background="acad.png">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3" id="cardd">
                <h2 class="text-center">LOGIN</h2><br>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab" aria-controls="login" aria-selected="true">Funcionário</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab" aria-controls="register" aria-selected="false">Área do Aluno</button>
                    </li>
                </ul>

                <div class="tab-content mt-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="loginNome" class="form-label">Nome de Usuário</label>
                                <input type="text" class="form-control" id="loginNome" name="nome" required>
                            </div>
                            <div class="mb-3">
                                <label for="loginSenha" class="form-label">Senha</label>
                                <input type="text" class="form-control" id="loginSenha" name="senha" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Entrar</button><br><br>
                        </form>
                        <button type="submit" name="liberarAcesso" id="liberarAcesso" class="btn btn-primary w-100">Liberar Acesso</button>
                    </div>

                    <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="registerNome" class="form-label">CPF do Aluno</label>
                                <input type="text" class="form-control" id="registerNome" name="nome" required><br>
                                <button type="submit" name="register" class="btn btn-success w-100">Acessar Ficha</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <footer>Todos os direitos reservados ©</footer>
    </div>
</body>

</html>