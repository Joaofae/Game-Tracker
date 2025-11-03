<?php 
$tituloPagina = "Login - Lista de Jogos";
include 'includes/header.php'; 
?>

<div class="container">
    <div class="form-container">
        
        <h2 class="text-center mb-4">Login</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php
                if ($_GET['erro'] == 'login_invalido') echo "E-mail ou senha incorretos.";
                if ($_GET['erro'] == 'campos_vazios') echo "Por favor, preencha todos os campos.";
                if ($_GET['erro'] == 'acesso_negado') echo "Você precisa estar logado para ver esta página.";
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['sucesso']) && $_GET['sucesso'] == 'cadastro_ok'): ?>
            <div class="alert alert-success" role="alert">
                Cadastro realizado com sucesso! Faça seu login.
            </div>
        <?php endif; ?>

        <form action="../controllers/auth_controller.php?action=login" method="POST">
            
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Entrar</button>
            </div>
            
        </form>

        <p class="text-center mt-3">
            Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>
        </p>
    </div>
</div>

<?php 
include 'includes/footer.php'; 
?>