<?php
require_once __DIR__ . '/../inc/functions.php';
require_admin();
$cards = [
    [
        'label' => 'Produtos',
        'value' => count(all_rows('products')),
        'href'  => base_path('admin/products.php'),
        'btn'   => 'Gerir produtos',
        'class' => 'btn-primary',
    ],
    [
        'label' => 'Produtos em destaque',
        'value' => featured_products_count(),
        'href'  => base_path('admin/products.php?filter=featured'),
        'btn'   => 'Gerir destaques',
        'class' => 'btn-secondary',
    ],
    [
        'label' => 'Categorias',
        'value' => count(all_rows('categories')),
        'href'  => base_path('admin/categories.php'),
        'btn'   => 'Gerir categorias',
        'class' => 'btn-secondary',
    ],
    [
        'label' => 'Serviços',
        'value' => count(all_rows('services')),
        'href'  => base_path('admin/services.php'),
        'btn'   => 'Gerir serviços',
        'class' => 'btn-secondary',
    ],
    [
        'label' => 'Utilizadores',
        'value' => count(all_rows('users')),
        'href'  => base_path('admin/users.php'),
        'btn'   => 'Gerir utilizadores',
        'class' => 'btn-secondary',
    ],
    [
        'label' => 'Pedidos',
        'value' => count(all_rows('orders')),
        'href'  => base_path('admin/orders.php'),
        'btn'   => 'Gerir pedidos',
        'class' => 'btn-secondary',
    ],
];
$pageTitle = 'Admin';
include __DIR__ . '/../inc/header.php';
?>
<div class="page-wrap">
  <div class="section-container">
    <div class="section-header"><h2>Painel do administrador</h2></div>
    <div class="admin-grid">
      <?php foreach($cards as $card): ?>
        <div class="stat-card admin-stat-card">
          <div class="admin-stat-top">
            <span><?= e($card['label']) ?></span>
            <strong><?= (int)$card['value'] ?></strong>
          </div>
          <a class="<?= e($card['class']) ?>" href="<?= e($card['href']) ?>"><?= e($card['btn']) ?></a>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../inc/footer.php'; ?>
