create database academiaCRUD;
use academiaCRUD;

-- login adicionar padrão admin 123
-- dentro a pessoa pode fazer uma requesição de login
-- ngm vai poder criar uma conta se o sistema n tiver liberado

create table login(
id int auto_increment primary key,
conta varchar(20) unique not null,
senha varchar(255) not null,
tipoDeConta varchar(20) not null,
-- Personal Trainer(personal), Gerência(func)
nomeCompletoPF varchar(255),
idade int,
cpf varchar(14),
formacao varchar(70),
-- dentro do sistema a pessoa vai colocar toda a informação e uma senha aleatória
liberado varchar(3) not null
-- sim ou nao
-- se não, bloquear login
)CHARACTER SET utf8 COLLATE utf8_bin;

-- quando a pessoa for criar a conta, se o nomedaconta e senha bater com o da requesição, vai aparecer
-- um novo campo pra nova senha
-- quando a nova senha for cadastrada, liberado troca para sim
create table requesicao(
id int auto_increment primary key,
nomeConta varchar(20) unique not null,
senhapadrão varchar(255) not null
)CHARACTER SET utf8 COLLATE utf8_bin;

create table areaAluno(
id_aluno int auto_increment primary key,
nomeCompletoAluno varchar(255) not null,
idade int not null,
cpf varchar(14) not null,
experiencia varchar(14) not null,
-- iniciante, intermediario, avançado, atleta, profissional
assistencia varchar(3) not null,
-- sim ou não
deficiencia varchar(3) not null
-- sim ou não
);

INSERT INTO login (id, conta, senha, tipoDeConta, liberado) VALUES
(1, 'admin', '123', 'func', 'sim');
