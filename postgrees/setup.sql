
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
    id_projeto INT,
    data_inicio DATE,
    data_fim DATE,
    FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto)
);

CREATE TABLE atribuicoes(
    id_atribuicoes SERIAL PRIMARY KEY NOT NULL,
    id_usuario INT,
    id_tarefa INT,
    data_atribuicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario),
    FOREIGN KEY (id_tarefa) REFERENCES tarefas(id_tarefa)
);

CREATE TABLE usuarios(
    id_usuario SERIAL PRIMARY KEY NOT NULL,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL
);