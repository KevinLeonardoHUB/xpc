<?php
require_once __DIR__ . '/inc/functions.php';
require_login();
$items = cart_items_detailed();
if (!$items) { set_flash('error', 'Adicione produtos ao carrinho antes do checkout.'); redirect_to('cart.php'); }
$user = current_user();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = create_order([
        'user_id'=>(int)$user['id'],
        'customer_name'=>trim($_POST['customer_name'] ?? ''),
        'customer_email'=>trim($_POST['customer_email'] ?? ''),
        'customer_phone'=>trim($_POST['customer_phone'] ?? ''),
        'address'=>trim($_POST['address'] ?? ''),
        'notes'=>trim($_POST['notes'] ?? ''),
        'total'=>cart_total(),
        'status'=>'novo',
        'created_at'=>date('Y-m-d H:i:s')
    ], $items);
    $_SESSION['cart']=[];
    set_flash('success', 'Compra finalizada com sucesso. Pedido #' . $orderId . ' criado.');
    redirect_to('account.php');
}
$pageTitle = 'Checkout'; include __DIR__ . '/inc/header.php'; ?>
<div class="page-wrap"><div class="section-container checkout-grid"><form method="post" class="auth-card"><h2>Finalizar compra</h2><input type="text" name="customer_name" value="<?= e($user['name']) ?>" required><input type="email" name="customer_email" value="<?= e($user['email']) ?>" required><input type="text" name="customer_phone" placeholder="Telefone"><textarea name="address" placeholder="Morada completa" rows="4" required></textarea><textarea name="notes" placeholder="Notas do pedido" rows="4"></textarea><button type="submit" class="btn-primary">Confirmar pedido</button></form><div class="table-card"><h3>Resumo</h3><?php foreach ($items as $item): ?><div class="summary-row"><span><?= e($item['name']) ?> x <?= (int)$item['quantity'] ?></span><strong><?= number_format((float)$item['subtotal'],2,',','.') ?>€</strong></div><?php endforeach; ?><hr><div class="summary-row"><span>Total</span><strong><?= number_format(cart_total(),2,',','.') ?>€</strong></div></div></div></div>
<?php include __DIR__ . '/inc/footer.php'; ?>