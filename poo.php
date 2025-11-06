<?php
// Atividade Referente a Disciplina de LTP III
// Alunos: João Vítor Piagem Pereira e Ingrid Costa Sousa 

abstract class Pessoa {
    protected $nome;
    protected $cpf;

    public function __construct($nome, $cpf) {
        $this->nome = $nome;
        $this->cpf = $cpf;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getCpf() {
        return $this->cpf;
    }

    abstract public function getInfo();
}

class Estudante extends Pessoa {
    private $curso;

    public function __construct($nome, $cpf, $curso) {
        parent::__construct($nome, $cpf);
        $this->curso = $curso;
    }

    public function getInfo() {
        return "Estudante - Nome: {$this->nome}, CPF: {$this->cpf}, Curso: {$this->curso}";
    }
}

class Professor extends Pessoa {
    private $funcao;
    private $salario;

    public function __construct($nome, $cpf, $funcao, $salario) {
        parent::__construct($nome, $cpf);
        $this->funcao = $funcao;
        $this->salario = $salario;
    }

    public function getInfo() {
        return "Professor - Nome: {$this->nome}, CPF: {$this->cpf}, Função: {$this->funcao}, Salário: R$ {$this->salario}";
    }
}


class Servidor extends Pessoa {
    private $funcao;
    private $salario;

    public function __construct($nome, $cpf, $funcao, $salario) {
        parent::__construct($nome, $cpf);
        $this->funcao = $funcao;
        $this->salario = $salario;
    }

    public function getInfo() {
        return "Servidor - Nome: {$this->nome}, CPF: {$this->cpf}, Função: {$this->funcao}, Salário: R$ {$this->salario}";
    }
}


class Visitante extends Pessoa {
    public function getInfo() {
        return "Visitante - Nome: {$this->nome}, CPF: {$this->cpf}";
    }
}


session_start();


if (!isset($_SESSION['pessoaIFTO'])) {
    $_SESSION['pessoaIFTO'] = [];
}

$pessoaIFTO = &$_SESSION['pessoaIFTO'];


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];

    switch ($tipo) {
        case 'estudante':
            $curso = $_POST['curso'];
            $pessoa = new Estudante($nome, $cpf, $curso);
            break;
        case 'professor':
            $funcao = $_POST['funcao'];
            $salario = $_POST['salario'];
            $pessoa = new Professor($nome, $cpf, $funcao, $salario);
            break;
        case 'servidor':
            $funcao = $_POST['funcao'];
            $salario = $_POST['salario'];
            $pessoa = new Servidor($nome, $cpf, $funcao, $salario);
            break;
        case 'visitante':
            $pessoa = new Visitante($nome, $cpf);
            break;
        default:
            $pessoa = null;
            break;
    }

    if ($pessoa) {
        $pessoaIFTO[] = $pessoa;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Pessoas - IFTO</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        h1 { color: #004080; }
        form { margin-bottom: 30px; padding: 15px; border: 1px solid #ccc; border-radius: 10px; width: 400px; }
        label { display: block; margin-top: 10px; }
        input, select { width: 100%; padding: 8px; margin-top: 4px; }
        button { margin-top: 15px; padding: 10px; background: #004080; color: white; border: none; border-radius: 5px; cursor: pointer; }
        ul { list-style: none; padding: 0; }
        li { background: #f5f5f5; margin-bottom: 8px; padding: 10px; border-radius: 5px; }
    </style>

    <script>
        function atualizarCampos() {
            const tipo = document.getElementById("tipo").value;
            document.getElementById("campoCurso").style.display = (tipo === "estudante") ? "block" : "none";
            document.getElementById("campoFuncao").style.display = (tipo === "professor" || tipo === "servidor") ? "block" : "none";
            document.getElementById("campoSalario").style.display = (tipo === "professor" || tipo === "servidor") ? "block" : "none";
        }
    </script>
</head>
<body>

    <h1>Cadastro de Pessoas - IFTO</h1>

    <form method="POST">
        <label>Nome:</label>
        <input type="text" name="nome" required>

        <label>CPF:</label>
        <input type="text" name="cpf" required>

        <label>Tipo de Pessoa:</label>
        <select name="tipo" id="tipo" onchange="atualizarCampos()" required>
            <option value="">Selecione</option>
            <option value="estudante">Estudante</option>
            <option value="professor">Professor</option>
            <option value="servidor">Servidor</option>
            <option value="visitante">Visitante</option>
        </select>

        <div id="campoCurso" style="display:none;">
            <label>Curso:</label>
            <input type="text" name="curso">
        </div>

        <div id="campoFuncao" style="display:none;">
            <label>Função:</label>
            <input type="text" name="funcao">
        </div>

        <div id="campoSalario" style="display:none;">
            <label>Salário:</label>
            <input type="number" name="salario" step="0.01">
        </div>

        <button type="submit">Cadastrar</button>
    </form>

    <h2>Pessoas Cadastradas</h2>
    <ul>
        <?php
        if (empty($pessoaIFTO)) {
            echo "<li>Nenhuma pessoa cadastrada ainda.</li>";
        } else {
            foreach ($pessoaIFTO as $p) {
                echo "<li>" . $p->getInfo() . "</li>";
            }
        }
        ?>
    </ul>

</body>
</html>
