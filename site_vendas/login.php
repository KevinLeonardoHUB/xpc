<?php
require_once __DIR__ . '/inc/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $user = find_user_by_email($email);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        set_flash('success', 'Login efetuado com sucesso.');
        redirect_to($user['role'] === 'admin' ? 'admin/index.php' : 'account.php');
    }
    set_flash('error', 'Email ou palavra-passe inválidos.');
    redirect_to('login.php');
}
$pageTitle = 'Login'; include __DIR__ . '/inc/header.php'; ?>
<div class="auth-wrapper"><form method="post" class="auth-card"><h2>Login</h2><input type="email" name="email" placeholder="Email" required><input type="password" name="password" placeholder="Palavra-passe" required><button type="submit" class="btn-primary">Entrar</button><p>Não tem conta? <a href="<?= e(base_path('register.php')) ?>">Registre-se</a></p></form></div>
<?php include __DIR__ . '/inc/footer.php'; ?>
