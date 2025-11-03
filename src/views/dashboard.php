<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?erro=acesso_negado");
    exit;
}

require_once '../../config/database.php';

$usuario_id = $_SESSION['usuario_id'];
$usuario_nome = $_SESSION['usuario_nome'];

try {
    $sql = "SELECT * FROM lista_pessoal_jogos WHERE id_usuario = ? ORDER BY data_adicionado DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $jogos = $stmt->fetchAll();

    $listas = [
        'quero_comprar' => [],
        'quero_jogar' => [],
        'finalizado' => [],
        'platinado' => []
    ];
    foreach ($jogos as $jogo) {
        $listas[$jogo['status']][] = $jogo;
    }
} catch (\PDOException $e) {
    die("Erro ao buscar jogos: ". $e->getMessage());
}

$tituloPagina = "Dashboard";
include 'includes/header.php'; 
?>

<nav class="navbar navbar-expand-lg navbar-light" style="background-color: rgb(204, 153, 255);">
    <div class="container">
    <a class="navbar-brand" href="#">Game Tracker</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
            <span class="navbar-text me-3">
              Olá, <?php echo htmlspecialchars($usuario_nome); ?>!
            </span>
        </li>
        <li class="nav-item">
          <a class="btn btn-outline-danger" href="../controllers/auth_controller.php?action=logout">Sair</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Jogo <?php echo htmlspecialchars($_GET['sucesso']); ?> com sucesso!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Erro: <?php echo htmlspecialchars($_GET['erro']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>


    <div class="accordion mb-4" id="accordionAddGame">
      <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
            Adicionar Novo Jogo
          </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionAddGame">
          <div class="accordion-body">
            <form action="../controllers/game_controller.php?action=create" method="POST">
                <div class="row g-3">
                    <div class="col-md-5"><label for="nome_jogo" class="form-label">Nome do Jogo</label><input type="text" class="form-control" id="nome_jogo" name="nome_jogo" required></div>
                    <div class="col-md-3"><label for="plataforma" class="form-label">Plataforma</label><input type="text" class="form-control" id="plataforma" name="plataforma" placeholder="Ex: PC, PS5..."></div>
                    <div class="col-md-3"><label for="status" class="form-label">Lista</label><select id="status" name="status" class="form-select" required><option value="quero_jogar" selected>Quero Jogar</option><option value="quero_comprar">Quero Comprar</option><option value="finalizado">Finalizado</option><option value="platinado">Platinado</option></select></div>
                    <div class="col-md-1 d-flex align-items-end"><button type="submit" class="btn btn-primary w-100">Adicionar</button></div>
                </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="d-flex justify-content-end mb-4">
        <a href="../controllers/report_controller.php" target="_blank" class="btn btn-secondary">
            Baixar Relatório em PDF
        </a>
    </div>

    <section class="list-container">
        
        <?php
        function exibirJogo($jogo) {
            ?>
            <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap">
                <div class="game-info me-auto pe-3">
                    <strong><?php echo htmlspecialchars($jogo['nome_jogo']); ?></strong>
                    <?php if (!empty($jogo['plataforma'])): ?>
                        <br><small class="text-muted"><?php echo htmlspecialchars($jogo['plataforma']); ?></small>
                    <?php endif; ?>
                </div>
                
                <div class="game-actions d-flex align-items-center mt-2 mt-md-0">
                    <form action="../controllers/game_controller.php?action=update" method="POST" class="d-flex me-2">
                        <input type="hidden" name="id_jogo" value="<?php echo $jogo['id']; ?>">
                        <select name="status" class="form-select form-select-sm" title="Mover para...">
                            <option value="quero_jogar" <?php echo ($jogo['status'] == 'quero_jogar') ? 'selected' : ''; ?>>Quero Jogar</option>
                            <option value="quero_comprar" <?php echo ($jogo['status'] == 'quero_comprar') ? 'selected' : ''; ?>>Quero Comprar</option>
                            <option value="finalizado" <?php echo ($jogo['status'] == 'finalizado') ? 'selected' : ''; ?>>Finalizado</option>
                            <option value="platinado" <?php echo ($jogo['status'] == 'platinado') ? 'selected' : ''; ?>>Platinado</option>
                        </select>
                        <button type="submit" class="btn btn-outline-primary btn-sm ms-1">Mover</button>
                    </form>

                    <a href="../controllers/game_controller.php?action=delete&id=<?php echo $jogo['id']; ?>" 
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Tem certeza que deseja excluir este jogo?');">
                       Excluir
                    </a>
                </div>
            </li>
            <?php
        }
        ?>

        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-3"><div class="card h-100"><div class="card-header bg-primary text-white"><h3>Quero Jogar</h3></div><ul class="list-group list-group-flush"><?php foreach ($listas['quero_jogar'] as $jogo) exibirJogo($jogo); if (empty($listas['quero_jogar'])) echo "<li class='list-group-item text-muted'>Não estou vendo muito movimento por aqui</li>"; ?></ul></div></div>
            <div class="col-12 col-md-6 col-lg-3"><div class="card h-100"><div class="card-header bg-info text-white"><h3>Quero Comprar</h3></div><ul class="list-group list-group-flush"><?php foreach ($listas['quero_comprar'] as $jogo) exibirJogo($jogo); if (empty($listas['quero_comprar'])) echo "<li class='list-group-item text-muted'>Não estou vendo muito movimento por aqui</li>"; ?></ul></div></div>
            <div class="col-12 col-md-6 col-lg-3"><div class="card h-100"><div class="card-header bg-success text-white"><h3>Finalizado</h3></div><ul class="list-group list-group-flush"><?php foreach ($listas['finalizado'] as $jogo) exibirJogo($jogo); if (empty($listas['finalizado'])) echo "<li class='list-group-item text-muted'>Não estou vendo muito movimento por aqui</li>"; ?></ul></div></div>
            <div class="col-12 col-md-6 col-lg-3"><div class="card h-100"><div class="card-header bg-warning text-dark"><h3>Platinado</h3></div><ul class="list-group list-group-flush"><?php foreach ($listas['platinado'] as $jogo) exibirJogo($jogo); if (empty($listas['platinado'])) echo "<li class='list-group-item text-muted'>Não estou vendo muito movimento por aqui</li>"; ?></ul></div></div>
        </div>
    </section>
</div>

<?php 
include 'includes/footer.php'; 
?>