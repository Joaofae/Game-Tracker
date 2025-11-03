<?php 
$tituloPagina = "Cadastro - Lista de Jogos";
include 'includes/header.php'; 
?>

<div class="container">
    <div class="form-container">
        
        <h2 class="text-center mb-4">Crie sua Conta</h2>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger" role="alert">
                <?php
                if ($_GET['erro'] == 'campos_vazios') echo "Por favor, preencha todos os campos.";
                if ($_GET['erro'] == 'email_existente') echo "Este e-mail já está cadastrado.";
                ?>
            </div>
        <?php endif; ?>

        <form action="../controllers/auth_controller.php?action=register" method="POST">
            
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            
            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            
            <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg">Cadastrar</button>
            </div>
            
        </form>

         <p class="text-center mt-3">
            Já tem uma conta? <a href="login.php">Faça login</a>
        </p>
    </div>
</div>

<?php include 'includes/footer.php'; ?>