<?php
require_once __DIR__ . '/inc/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $productId = (int) ($_POST['product_id'] ?? 0);
    $_SESSION['cart'] ??= [];
    if ($action === 'add' && $productId > 0) {
        $_SESSION['cart'][$productId] = ($_SESSION['cart'][$productId] ?? 0) + 1;
        set_flash('success', 'Produto adicionado ao carrinho.');
    }
    if ($action === 'update') {
        foreach ($_POST['qty'] ?? [] as $id => $qty) {
            $qty = max(0, (int) $qty);
            if ($qty === 0) unset($_SESSION['cart'][$id]); else $_SESSION['cart'][$id] = $qty;
        }
        set_flash('success', 'Carrinho atualizado.');
    }
    if ($action === 'remove' && $productId > 0) {
        unset($_SESSION['cart'][$productId]);
        set_flash('success', 'Produto removido.');
    }
    redirect_to('cart.php');
}
$items = cart_items_detailed();
$pageTitle = 'Carrinho'; include __DIR__ . '/inc/header.php'; ?>
<div class="page-wrap"><div class="section-container"><div class="section-header"><h2>Carrinho</h2></div><?php if (!$items): ?><div class="auth-card"><p>O carrinho está vazio.</p><a class="btn-primary" href="<?= e(base_path('index.php')) ?>#produtos">Ver produtos</a></div><?php else: ?><form method="post" class="table-card"><input type="hidden" name="action" value="update"><table class="data-table"><thead><tr><th>Produto</th><th>Preço</th><th>Qtd</th><th>Subtotal</th><th></th></tr></thead><tbody><?php foreach ($items as $item): ?><tr><td><?= e($item['name']) ?></td><td><?= number_format((float)$item['price'],2,',','.') ?>€</td><td><input type="number" min="0" name="qty[<?= (int)$item['id'] ?>]" value="<?= (int)$item['quantity'] ?>"></td><td><?= number_format((float)$item['subtotal'],2,',','.') ?>€</td><td><button type="submit" name="action" value="remove">Remover</button><input type="hidden" name="product_id" value="<?= (int)$item['id'] ?>"></td></tr><?php endforeach; ?></tbody></table><div class="cart-actions"><strong>Total: <?= number_format(cart_total(),2,',','.') ?>€</strong><div><button type="submit" class="btn-secondary">Atualizar</button><a class="btn-primary" href="<?= e(base_path('checkout.php')) ?>">Finalizar compra</a></div></div></form><?php endif; ?></div></div>
<?php include __DIR__ . '/inc/footer.php'; ?>
