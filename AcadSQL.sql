create database academiaCRUD;
use academiaCRUD;

create table loginCadastro(
id int auto_increment primary key,
conta varchar(25) unique not null,
senha varchar(255) not null,
tipoDeConta varchar(20) not null,
-- Personal Trainer(pers), Funcionário(func), Gerência(gen)
nomeCompletoPF varchar(200) not null,
idade date not null,
genero varchar(9) not null,
-- masculino, feminino, outro
cpf varchar(11) not null,
formacao varchar(100) not null
-- dentro do sistema a pessoa vai colocar toda a informação e uma senha aleatória
)CHARACTER SET utf8 COLLATE utf8_bin;

create table areaAluno(
id_aluno int auto_increment primary key,
nomeCompletoAluno varchar(200) not null,
idade date not null,
genero varchar(9) not null,
-- masculino, feminino, outro
cpf varchar(11) unique not null,
experiencia varchar(14) not null,
-- iniciante, intermediario, avançado, atleta, profissional
assistencia varchar(3) not null,
-- sim ou não
deficiencia varchar(3) not null,
alunoDefault varchar(10) default("Aluno")
);

INSERT INTO loginCadastro (id, conta, senha, tipoDeConta, nomeCompletoPF, idade, genero, cpf, formacao) VALUES
(1, 'admin', '123', 'Gerente', 'Administrador Geral', '2024-12-11', 'Outro', '00000000000', 'Dono');
