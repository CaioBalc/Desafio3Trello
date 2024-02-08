
CREATE TABLE projetos(
    id_projeto SERIAL PRIMARY KEY NOT NULL,
    nome_projeto VARCHAR(255) NOT NULL,
    descricao_projeto TEXT,
    data_inicio DATE,
    data_fim DATE
);


CREATE TABLE tarefas(
    id_tarefa SERIAL PRIMARY KEY NOT NULL,
    descricao_tarefa TEXT NOT NULL,
    projeto_id INT,
    data_inicio DATE,
    data_fim DATE
);

CREATE TABLE atribuicoes(
    id_atribuicoes SERIAL PRIMARY KEY NOT NULL,
    usuario_id INT,
    tarefa_id INT,
    data_atribuicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE usuarios(
    id_usuarios SERIAL PRIMARY KEY NOT NULL,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);