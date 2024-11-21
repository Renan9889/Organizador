<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Cadastro de Produtos</title>
</head>
<body>
    <h1>Cadastro de Produtos</h1>
    <form action="produtos.php" method="POST">
        <input type="hidden" name="action" value="create">
        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br><br>
        
        <label for="descricao">Descrição:</label><br>
        <textarea id="descricao" name="descricao" required></textarea><br><br>
        
        <label for="preco">Preço:</label><br>
        <input type="number" id="preco" name="preco" step="0.01" required><br><br>
        
        <label for="quantidade">Quantidade:</label><br>
        <input type="number" id="quantidade" name="quantidade" required><br><br>
        
        <button type="submit">Cadastrar Produto</button>
    </form>

    <h2>Pesquisar Produto</h2>
    <form action="produtos.php" method="GET">
        <input type="text" name="query" placeholder="Nome do produto">
        <button type="submit">Pesquisar</button>
    </form>

    <h2>Lista de Produtos</h2>
    <table border="1">
        <tr>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Ações</th>
        </tr>
        <?php
        $produtos = json_decode(file_get_contents('dados.json'), true) ?? [];
        foreach ($produtos as $id => $produto): ?>
        <tr>
            <td><?= htmlspecialchars($produto['nome']) ?></td>
            <td><?= htmlspecialchars($produto['descricao']) ?></td>
            <td><?= number_format($produto['preco'], 2, ',', '.') ?></td>
            <td><?= $produto['quantidade'] ?></td>
            <td>
                <form action="produtos.php" method="POST" style="display:inline;">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button type="submit">Excluir</button>
                </form>
                <form action="produtos.php" method="GET" style="display:inline;">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="id" value="<?= $id ?>">
                    <button type="submit">Editar</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
