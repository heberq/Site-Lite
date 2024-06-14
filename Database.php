<?php
class Database {
    private $db;

    public function __construct() {
        $this->db = new SQLite3('meu_banco_de_dados.db');
        $this->db->exec('CREATE TABLE IF NOT EXISTS arquivos (id INTEGER PRIMARY KEY, nome TEXT, pasta TEXT, conteudo TEXT)');
    }

    public function inserir($nome, $conteudo, $pasta) {
        $stmt = $this->db->prepare("INSERT INTO arquivos (nome, conteudo, pasta) VALUES (:nome, :conteudo, :pasta)");
        $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
        $stmt->bindValue(':conteudo', $conteudo, SQLITE3_TEXT);
        $stmt->bindValue(':pasta', $pasta, SQLITE3_TEXT);
        $stmt->execute();
    }

    public function atualizar($id, $nome, $conteudo, $pasta) {
        $stmt = $this->db->prepare("UPDATE arquivos SET nome = :nome, conteudo = :conteudo, pasta = :pasta WHERE id = :id");
        $stmt->bindValue(':nome', $nome, SQLITE3_TEXT);
        $stmt->bindValue(':conteudo', $conteudo, SQLITE3_TEXT);
        $stmt->bindValue(':pasta', $pasta, SQLITE3_TEXT);
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
    }

    public function excluir($id) {
        $stmt = $this->db->prepare("DELETE FROM arquivos WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $stmt->execute();
    }

    public function buscar() {
        return $this->db->query("SELECT * FROM arquivos");
    }

    public function buscarArquivo($id) {
        $stmt = $this->db->prepare("SELECT * FROM arquivos WHERE id = :id");
        $stmt->bindValue(':id', $id, SQLITE3_INTEGER);
        $result = $stmt->execute();
        return $result->fetchArray();
    }

    public function gerarArquivoHTML($nome, $conteudo, $pasta) {
    if (!file_exists($pasta)) {
        mkdir($pasta, 0777, true);
        }
        $arquivo = fopen("{$pasta}/{$nome}.html", 'w');
        fwrite($arquivo, $conteudo);
        fclose($arquivo);
    }
}
?>