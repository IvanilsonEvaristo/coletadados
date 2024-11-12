//Criar tabelas de banco de dados//

CREATE DATABASE devs_do_rn;
USE devs_do_rn;

-- Tabela para Associados
CREATE TABLE associados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    data_filiacao DATE NOT NULL
);

-- Tabela para Anuidades
CREATE TABLE anuidades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ano INT NOT NULL,
    valor DECIMAL(10, 2) NOT NULL
);

-- Tabela para Pagamentos de Anuidades
CREATE TABLE pagamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    associado_id INT NOT NULL,
    anuidade_id INT NOT NULL,
    pago BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (associado_id) REFERENCES associados(id),
    FOREIGN KEY (anuidade_id) REFERENCES anuidades(id)
);

//-----------------------------------------------------------------------------------------------//

//Configuração de banco de dados//

<?php
$host = 'localhost';
$dbname = 'devs_do_rn';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro na conexão com o banco de dados: " . $e->getMessage();
}
?>

<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $data_filiacao = $_POST['data_filiacao'];

    $sql = "INSERT INTO associados (nome, email, cpf, data_filiacao) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $cpf, $data_filiacao]);
}

$sql = "SELECT * FROM associados";
$stmt = $pdo->query($sql);
$associados = $stmt->fetchAll();
?>

//----------------------------------------------------------------------------------------------



//Cadastrando associados//

<h2>Cadastro de Associados</h2>
<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    CPF: <input type="text" name="cpf" required><br>
    Data de Filiação: <input type="date" name="data_filiacao" required><br>
    <button type="submit">Cadastrar</button>
</form>

<h2>Lista de Associados</h2>
<ul>
<?php foreach ($associados as $assoc) { ?>
    <li><?= htmlspecialchars($assoc['nome']) . " - " . htmlspecialchars($assoc['email']); ?></li>
<?php } ?>
</ul>


//--------------------------------------------------------------------------------------------------



//Cadastro de anuidades//

<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cpf = $_POST['cpf'];
    $data_filiacao = $_POST['data_filiacao'];

    $sql = "INSERT INTO associados (nome, email, cpf, data_filiacao) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $cpf, $data_filiacao]);
}

$sql = "SELECT * FROM associados";
$stmt = $pdo->query($sql);
$associados = $stmt->fetchAll();
?>

<h2>Cadastro de Associados</h2>
<form method="POST">
    Nome: <input type="text" name="nome" required><br>
    Email: <input type="email" name="email" required><br>
    CPF: <input type="text" name="cpf" required><br>
    Data de Filiação: <input type="date" name="data_filiacao" required><br>
    <button type="submit">Cadastrar</button>
</form>

<h2>Lista de Associados</h2>
<ul>
<?php foreach ($associados as $assoc) { ?>
    <li><?= htmlspecialchars($assoc['nome']) . " - " . htmlspecialchars($assoc['email']); ?></li>
<?php } ?>
</ul>


//---------------------------------------------------------------------------------------------------


//Gerenciamento de pagamentos//

<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ano = $_POST['ano'];
    $valor = $_POST['valor'];

    $sql = "INSERT INTO anuidades (ano, valor) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ano, $valor]);
}

$sql = "SELECT * FROM anuidades";
$stmt = $pdo->query($sql);
$anuidades = $stmt->fetchAll();
?>

<h2>Cadastro de Anuidades</h2>
<form method="POST">
    Ano: <input type="number" name="ano" required><br>
    Valor: <input type="number" step="0.01" name="valor" required><br>
    <button type="submit">Cadastrar</button>
</form>

<h2>Lista de Anuidades</h2>
<ul>
<?php foreach ($anuidades as $anuidade) { ?>
    <li><?= htmlspecialchars($anuidade['ano']) . " - R$ " . htmlspecialchars($anuidade['valor']); ?></li>
<?php } ?>
</ul>


//----------------------------------------------------------------------------------------------




<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $associado_id = $_POST['associado_id'];
    $anuidade_id = $_POST['anuidade_id'];

    $sql = "UPDATE pagamentos SET pago = TRUE WHERE associado_id = ? AND anuidade_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$associado_id, $anuidade_id]);
}

$sql = "
    SELECT associados.nome, anuidades.ano, anuidades.valor, pagamentos.pago 
    FROM pagamentos
    JOIN associados ON pagamentos.associado_id = associados.id
    JOIN anuidades ON pagamentos.anuidade_id = anuidades.id";
$stmt = $pdo->query($sql);
$pagamentos = $stmt->fetchAll();
?>

<h2>Pagamentos de Anuidades</h2>
<form method="POST">
    Associado ID: <input type="number" name="associado_id" required><br>
    Anuidade ID: <input type="number" name="anuidade_id" required><br>
    <button type="submit">Pagar</button>
</form>

<h2>Lista de Pagamentos</h2>
<ul>
<?php foreach ($pagamentos as $pagamento) { ?>
    <li><?= htmlspecialchars($pagamento['nome']) . " - " . htmlspecialchars($pagamento['ano']) . ": " . ($pagamento['pago'] ? "Pago" : "Pendente"); ?></li>
<?php } ?>
</ul>

