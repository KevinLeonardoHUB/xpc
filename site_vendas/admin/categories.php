<?php
require_once __DIR__ . '/../inc/functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        save_category([
            'id'=>(int)($_POST['id'] ?? 0),
            'name'=>trim($_POST['name'] ?? ''),
            'slug'=>trim($_POST['slug'] ?? '') ?: slugify($_POST['name'] ?? ''),
            'icon'=>trim($_POST['icon'] ?? '') ?: 'fa-cubes',
        ]);
        set_flash('success', !empty($_POST['id']) ? 'Categoria atualizada.' : 'Categoria criada.');
        redirect_to('admin/categories.php');
    }
    if ($action === 'delete') {
        delete_row('categories', (int)$_POST['id']);
        set_flash('success', 'Categoria removida.');
        redirect_to('admin/categories.php');
    }
}
$edit = !empty($_GET['edit']) ? find_category((int)$_GET['edit']) : null;
$categories = categories_all();
foreach($categories as &$category){ $category['total_products']=count(array_filter(all_rows('products'), fn($p)=>(int)$p['category_id']===(int)$category['id'])); }
$pageTitle = 'Gerir categorias'; include __DIR__ . '/../inc/header.php'; ?>
<div class="page-wrap"><div class="section-container"><div class="section-header"><h2>Gerir categorias</h2><p>Adicione um símbolo Font Awesome para cada categoria.</p></div><div class="checkout-grid"><form method="post" class="auth-card"><h3><?= $edit ? 'Editar categoria' : 'Nova categoria' ?></h3><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>"><input type="text" name="name" placeholder="Nome" value="<?= e($edit['name'] ?? '') ?>" required><input type="text" name="slug" placeholder="Slug" value="<?= e($edit['slug'] ?? '') ?>"><input type="text" name="icon" placeholder="Ex.: fa-server" value="<?= e($edit['icon'] ?? 'fa-cubes') ?>" required><div class="category-icon-preview"><i class="fa <?= e($edit['icon'] ?? 'fa-cubes') ?>"></i><span><?= e($edit['icon'] ?? 'fa-cubes') ?></span></div><small class="form-help">Exemplos: fa-server, fa-plug, fa-hdd-o, fa-microchip, fa-sitemap.</small><button type="submit" class="btn-primary">Guardar</button></form><div class="table-card"><table class="data-table"><thead><tr><th>Categoria</th><th>Ícone</th><th>Slug</th><th>Produtos</th><th></th></tr></thead><tbody><?php foreach($categories as $category): ?><tr><td><?= e($category['name']) ?></td><td><i class="fa <?= e($category['icon'] ?? 'fa-cubes') ?>"></i> <?= e($category['icon'] ?? 'fa-cubes') ?></td><td><?= e($category['slug']) ?></td><td><?= (int)$category['total_products'] ?></td><td><a href="?edit=<?= (int)$category['id'] ?>">Editar</a> <form method="post" class="inline-form" onsubmit="return confirm('Remover categoria e produtos associados?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$category['id'] ?>"><button type="submit">Apagar</button></form></td></tr><?php endforeach; ?></tbody></table></div></div></div></div>
<?php include __DIR__ . '/../inc/footer.php'; ?>
