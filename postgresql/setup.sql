
CREATE TABLE usuarios (
    id_usuario SERIAL PRIMARY KEY,
    nome_usuario VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL 
);

CREATE TABLE projetos (
    id_projeto SERIAL PRIMARY KEY,
    nome_projeto VARCHAR(255) NOT NULL,
    descricao_projeto TEXT,
    data_inicio DATE,
    data_fim DATE
);

CREATE TABLE tarefas (
    id_tarefa SERIAL PRIMARY KEY,
    descricao_tarefa TEXT NOT NULL,
    id_projeto INT,
    data_inicio DATE,
    data_fim DATE,
    FOREIGN KEY (id_projeto) REFERENCES projetos (id_projeto) ON DELETE CASCADE -- Ação em cascata ao deletar um projeto
);

CREATE TABLE atribuicoes (
    id_atribuicao SERIAL PRIMARY KEY,
    id_usuario INT,
    id_tarefa INT,
    data_atribuicao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario) ON DELETE CASCADE,
    FOREIGN KEY (id_tarefa) REFERENCES tarefas (id_tarefa) ON DELETE CASCADE
);
