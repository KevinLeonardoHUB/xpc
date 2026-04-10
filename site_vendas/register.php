<?php
require_once __DIR__ . '/inc/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';
    if ($password !== $confirm) { set_flash('error', 'As palavras-passe não coincidem.'); redirect_to('register.php'); }
    if (strlen($password) < 6) { set_flash('error', 'A palavra-passe deve ter pelo menos 6 caracteres.'); redirect_to('register.php'); }
    if (find_user_by_email($email)) { set_flash('error', 'Já existe uma conta com esse email.'); redirect_to('register.php'); }
    $user = create_user($name, $email, $password, 'customer');
    $_SESSION['user_id'] = $user['id'];
    set_flash('success', 'Conta criada com sucesso.');
    redirect_to('account.php');
}
$pageTitle = 'Registro'; include __DIR__ . '/inc/header.php'; ?>
<div class="auth-wrapper"><form method="post" class="auth-card"><h2>Criar conta</h2><input type="text" name="name" placeholder="Nome" required><input type="email" name="email" placeholder="Email" required><input type="password" name="password" placeholder="Palavra-passe" required><input type="password" name="password_confirm" placeholder="Confirmar palavra-passe" required><button type="submit" class="btn-primary">Registrar</button></form></div>
<?php include __DIR__ . '/inc/footer.php'; ?>