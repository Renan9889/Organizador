<?php
$dataFile = 'dados.json';

function loadData() {
    global $dataFile;
    return json_decode(file_get_contents($dataFile), true) ?? [];
}

function saveData($data) {
    global $dataFile;
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    $produtos = loadData();

    if ($action === 'create') {
        // Cadastrar novo produto
        $produtos[] = [
            'nome' => $_POST['nome'],
            'descricao' => $_POST['descricao'],
            'preco' => (float) $_POST['preco'],
            'quantidade' => (int) $_POST['quantidade']
        ];
        saveData($produtos);
    } elseif ($action === 'delete') {
        // Excluir produto
        $id = $_POST['id'];
        unset($produtos[$id]);
        saveData(array_values($produtos));
    } elseif ($action === 'update') {
        // Atualizar produto
        $id = $_POST['id'];
        $produtos[$id] = [
            'nome' => $_POST['nome'],
            'descricao' => $_POST['descricao'],
            'preco' => (float) $_POST['preco'],
            'quantidade' => (int) $_POST['quantidade']
        ];
        saveData($produtos);
    }
    header('Location: index.php');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';

    if ($action === 'edit') {
        // Editar produto
        $produtos = loadData();
        $id = $_GET['id'];
        $produto = $produtos[$id];
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Editar Produto</title>
        </head>
        <body>
            <h1>Editar Produto</h1>
            <form action="produtos.php" method="POST">
                <input type="hidden" name="action" value="update">
                <input type="hidden" name="id" value="<?= $id ?>">
                <label for="nome">Nome:</label><br>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required><br><br>
                
                <label for="descricao">Descrição:</label><br>
                <textarea id="descricao" name="descricao" required><?= htmlspecialchars($produto['descricao']) ?></textarea><br><br>
                
                <label for="preco">Preço:</label><br>
                <input type="number" id="preco" name="preco" step="0.01" value="<?= $produto['preco'] ?>" required><br><br>
                
                <label for="quantidade">Quantidade:</label><br>
                <input type="number" id="quantidade" name="quantidade" value="<?= $produto['quantidade'] ?>" required><br><br>
                
                <button type="submit">Salvar Alterações</button>
            </form>
        </body>
        </html>
        <?php
        exit;
    } elseif (!empty($_GET['query'])) {
        // Pesquisa de produtos
        $query = strtolower($_GET['query']);
        $produtos = loadData();
        $resultados = array_filter($produtos, function($produto) use ($query) {
            return strpos(strtolower($produto['nome']), $query) !== false;
        });
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Resultados da Pesquisa</title>
        </head>
        <body>
            <h1>Resultados da Pesquisa</h1>
            <a href="index.php">Voltar</a>
            <table border="1">
                <tr>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                </tr>
                <?php foreach ($resultados as $produto): ?>
                <tr>
                    <td><?= htmlspecialchars($produto['nome']) ?></td>
                    <td><?= htmlspecialchars($produto['descricao']) ?></td>
                    <td><?= number_format($produto['preco'], 2, ',', '.') ?></td>
                    <td><?= $produto['quantidade'] ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </body>
        </html>
        <?php
        exit;
    }
}
