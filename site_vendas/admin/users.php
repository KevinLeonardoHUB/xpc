<?php
require_once __DIR__ . '/../inc/functions.php';
require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $user = find_user_by_id($id);
        $current = current_user();

        if (!$user) {
            set_flash('error', 'Utilizador não encontrado.');
        } elseif ($current && (int) $current['id'] === $id) {
            set_flash('error', 'Não pode remover a sua própria conta enquanto está autenticado.');
        } elseif (($user['role'] ?? 'customer') === 'admin' && admin_users_count() <= 1) {
            set_flash('error', 'Não é possível remover o último administrador.');
        } else {
            delete_row('users', $id);
            set_flash('success', 'Utilizador removido com sucesso.');
        }

        redirect_to('admin/users.php');
    }
}

$users = all_rows('users');
usort($users, fn($a, $b) => strcmp($b['created_at'], $a['created_at']));
$pageTitle = 'Utilizadores';
include __DIR__ . '/../inc/header.php';
?>
<div class="page-wrap">
  <div class="section-container">
    <div class="section-header">
      <h2>Utilizadores</h2>
      <p>O administrador pode consultar e remover utilizadores, com proteção para a própria conta e para o último admin.</p>
    </div>

    <div class="table-card">
      <table class="data-table">
        <thead>
          <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Perfil</th>
            <th>Criado em</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
            <?php
              $isCurrentUser = current_user() && (int) current_user()['id'] === (int) $user['id'];
              $isLastAdmin = ($user['role'] ?? 'customer') === 'admin' && admin_users_count() <= 1;
            ?>
            <tr>
              <td><?= e($user['name']) ?><?= $isCurrentUser ? ' <small>(você)</small>' : '' ?></td>
              <td><?= e($user['email']) ?></td>
              <td><?= e($user['role']) ?></td>
              <td><?= e($user['created_at']) ?></td>
              <td>
                <?php if ($isCurrentUser): ?>
                  <span class="text-muted">Sessão atual</span>
                <?php elseif ($isLastAdmin): ?>
                  <span class="text-muted">Último admin</span>
                <?php else: ?>
                  <form method="post" class="inline-form" onsubmit="return confirm('Tem a certeza que quer remover este utilizador?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $user['id'] ?>">
                    <button type="submit">Remover</button>
                  </form>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../inc/footer.php'; ?>
