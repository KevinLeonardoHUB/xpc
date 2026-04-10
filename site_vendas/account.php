<?php
require_once __DIR__ . '/inc/functions.php';
require_login();
$user = current_user();
$orders = orders_by_user((int)$user['id']);
$pageTitle = 'Minha conta'; include __DIR__ . '/inc/header.php'; ?>
<div class="page-wrap"><div class="section-container"><div class="section-header"><h2>Minha conta</h2><p><?= e($user['name']) ?> · <?= e($user['email']) ?></p></div><div class="table-card"><h3>Meus pedidos</h3><?php if (!$orders): ?><p>Ainda não existem pedidos.</p><?php else: ?><table class="data-table"><thead><tr><th>ID</th><th>Data</th><th>Total</th><th>Status</th></tr></thead><tbody><?php foreach ($orders as $order): ?><tr><td>#<?= (int)$order['id'] ?></td><td><?= e($order['created_at']) ?></td><td><?= number_format((float)$order['total'],2,',','.') ?>€</td><td><?= e($order['status']) ?></td></tr><?php endforeach; ?></tbody></table><?php endif; ?></div></div></div>
<?php include __DIR__ . '/inc/footer.php'; ?>