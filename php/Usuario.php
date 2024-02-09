<?php
require_once 'Conexao.php';

class Usuario {
    private $conexaoBanco;
    private $nome;
    private $email;
    private $idUsuario;

    public function __construct() 
    {
        $this->conexaoBanco = Conexao::conectar(); 
    }

    public function criaUsuario(string $nome, string $email)
    {
        try {
           
            if (empty($nome)) {
                throw new Exception("O nome do usuário é obrigatório.");
            }
            if (empty($email)) {
                throw new Exception("O email do usuário é obrigatório.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("O email fornecido é inválido.");
            }

            
            $this->nome = $nome;
            $this->email = $email;

            
            $this->salvaUsuario();

            echo "Usuário criado com sucesso.\n";
        } catch (Exception $e) {
            echo "Erro ao criar usuário: " . $e->getMessage();
        }
    }

    private function salvaUsuario() 
    {
        $sql = "INSERT INTO usuarios (nome_usuario, email) VALUES (?, ?) RETURNING id_usuario";
        try {
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([
                $this->nome,
                $this->email
            ]);
    
            
            $this->idUsuario = $stmt->fetch(PDO::FETCH_ASSOC)['id_usuario'];
    
            echo "Usuário salvo com sucesso e ID recuperado: " . $this->idUsuario . "\n";
        } catch (PDOException $e) {
            exit('Erro ao salvar usuário no banco de dados: ' . $e->getMessage());
        }
    }

    
    public function pegaUsuarioDoBanco($id) {
        try {
            $sql = "SELECT id_usuario, nome_usuario, email FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
            $stmt->execute([$id]);
    
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    
            if ($resultado) {
                $this->idUsuario = $resultado['id_usuario'];
                $this->nome = $resultado['nome_usuario'];
                $this->email = $resultado['email'];
                echo "Usuário recuperado com sucesso: " . $this->nome . "\n";
            } else {
                throw new Exception("Usuário não encontrado.");
            }
        } catch (PDOException $e) {
            exit('Erro ao recuperar usuário do banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
    
    public function editaUsuario($nome, $email) {
        try {
           
            if (empty($this->idUsuario)) {
                throw new Exception('Sem ID de referência. Adicione um buscando um do banco de dados
                 com esseusuario->pegaUsuarioDoBanco($id) ou criando um novo com esseusuario->criaUsuario($nome, $email)');
            }
    
           
            if (empty($nome)) {
                throw new Exception("O nome do usuário é obrigatório.");
            }
            if (empty($email)) {
                throw new Exception("O email do usuário é obrigatório.");
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("O email fornecido é inválido.");
            }
    
            $sql = "UPDATE usuarios SET nome_usuario = ?, email = ? WHERE id_usuario = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
            $success = $stmt->execute([$nome, $email, $this->idUsuario]);
    
            if ($success && $stmt->rowCount() > 0) {
                $this->nome = $nome;
                $this->email = $email;
                echo "Usuário editado com sucesso.";
            } else {
              
                throw new Exception("Nenhuma alteração realizada. Verifique se o ID do usuário está correto.");
            }
        } catch (PDOException $e) {
            exit('Erro ao editar usuário no banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }
    
    public function deletaUsuario() {
        try {
            $sql = "DELETE FROM usuarios WHERE id_usuario = ?";
            $stmt = $this->conexaoBanco->prepare($sql);
            $success = $stmt->execute([$this->idUsuario]);
    
            if ($success && $stmt->rowCount() > 0) {
                $this->idUsuario = null;
                $this->nome = null;
                $this->email = null;
                echo "Usuário deletado com sucesso.";
            } else {
                throw new Exception('Nenhuma alteração realizada. Verifique se o ID do usuário está correto ou Sem ID de referência. Adicione um buscando um do banco de dados
                com esseusuario->pegaUsuarioDoBanco($id)');
            }
        } catch (PDOException $e) {
            exit('Erro ao deletar usuário no banco de dados: ' . $e->getMessage());
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    public function getIdUsuario(): ?int {
        return $this->idUsuario;
    }

    public function getNome(): string {
        return $this->nome;
    }

    public function getEmail(): string {
        return $this->email;
    }
}
