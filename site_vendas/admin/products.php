<?php
require_once __DIR__ . '/../inc/functions.php';
require_admin();

$filter = ($_GET['filter'] ?? '') === 'featured' ? 'featured' : 'all';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'save') {
        $id = (int) ($_POST['id'] ?? 0);
        $existing = $id ? find_product($id) : null;
        try {
            $image = upload_image('image', $existing['image'] ?? null);
            save_product([
                'id' => $id,
                'category_id' => (int) $_POST['category_id'],
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? '') ?: slugify($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float) $_POST['price'],
                'stock' => (int) $_POST['stock'],
                'image' => $image,
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'is_featured' => isset($_POST['is_featured']) ? 1 : 0,
            ]);
            set_flash('success', !empty($_POST['id']) ? 'Produto atualizado.' : 'Produto criado.');
        } catch (RuntimeException $e) {
            set_flash('error', $e->getMessage());
        }
        redirect_to('admin/products.php' . ($filter === 'featured' ? '?filter=featured' : ''));
    }

    if ($action === 'toggle_featured') {
        $id = (int) ($_POST['id'] ?? 0);
        $product = find_product($id);
        if ($product) {
            save_product(array_merge($product, [
                'id' => $id,
                'is_featured' => ((int) ($product['is_featured'] ?? 0) === 1) ? 0 : 1,
            ]));
            set_flash('success', ((int) ($product['is_featured'] ?? 0) === 1) ? 'Produto removido dos destaques.' : 'Produto adicionado aos destaques.');
        }
        redirect_to('admin/products.php' . ($filter === 'featured' ? '?filter=featured' : ''));
    }

    if ($action === 'delete') {
        $product = find_product((int) $_POST['id']);
        if ($product) {
            delete_uploaded_file($product['image'] ?? null);
        }
        delete_row('products', (int) $_POST['id']);
        set_flash('success', 'Produto removido.');
        redirect_to('admin/products.php' . ($filter === 'featured' ? '?filter=featured' : ''));
    }
}

$edit = !empty($_GET['edit']) ? find_product((int) $_GET['edit']) : null;
$products = all_products();
foreach ($products as &$product) {
    $cat = find_category((int) $product['category_id']);
    $product['category_name'] = $cat['name'] ?? '';
}
unset($product);

if ($filter === 'featured') {
    $products = array_values(array_filter($products, fn($product) => !empty($product['is_featured'])));
}

$categories = categories_all();
$pageTitle = 'Gerir produtos';
include __DIR__ . '/../inc/header.php';
?>
<div class="page-wrap">
  <div class="section-container">
    <div class="section-header">
      <h2><?= $filter === 'featured' ? 'Gerir produtos em destaque' : 'Gerir produtos' ?></h2>
      <p><?= $filter === 'featured'
            ? 'Aqui vê apenas os produtos marcados em destaque para a homepage.'
            : 'Agora também pode marcar produtos em destaque para aparecerem no bloco principal da homepage.' ?></p>
      <p>
        <a href="<?= e(base_path('admin/products.php')) ?>">Ver todos</a>
        &nbsp;·&nbsp;
        <a href="<?= e(base_path('admin/products.php?filter=featured')) ?>">Ver apenas destaques</a>
      </p>
    </div>

    <div class="checkout-grid">
      <form method="post" enctype="multipart/form-data" class="auth-card">
        <h3><?= $edit ? 'Editar produto' : 'Novo produto' ?></h3>
        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">

        <select name="category_id" required>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= (int) $cat['id'] ?>" <?= ((int) ($edit['category_id'] ?? 0) === (int) $cat['id']) ? 'selected' : '' ?>><?= e($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>

        <input type="text" name="name" placeholder="Nome" value="<?= e($edit['name'] ?? '') ?>" required>
        <input type="text" name="slug" placeholder="Slug" value="<?= e($edit['slug'] ?? '') ?>">
        <textarea name="description" rows="4" placeholder="Descrição" required><?= e($edit['description'] ?? '') ?></textarea>
        <input type="number" step="0.01" name="price" placeholder="Preço" value="<?= e((string) ($edit['price'] ?? '')) ?>" required>
        <input type="number" name="stock" placeholder="Stock" value="<?= e((string) ($edit['stock'] ?? '0')) ?>" required>

        <label class="upload-field">Imagem do produto
          <input type="file" name="image" accept="image/*">
        </label>

        <?php if (!empty($edit['image'])): ?>
          <img src="<?= e(image_url($edit['image'])) ?>" alt="Imagem do produto" class="admin-thumb">
        <?php endif; ?>

        <label class="checkline"><input type="checkbox" name="is_active" <?= !isset($edit['is_active']) || $edit['is_active'] ? 'checked' : '' ?>> Produto ativo</label>
        <label class="checkline"><input type="checkbox" name="is_featured" <?= !empty($edit['is_featured']) ? 'checked' : '' ?>> Produto em destaque</label>
        <button type="submit" class="btn-primary">Guardar</button>
      </form>

      <div class="table-card">
        <table class="data-table">
          <thead>
            <tr>
              <th>Imagem</th>
              <th>Produto</th>
              <th>Categoria</th>
              <th>Preço</th>
              <th>Stock</th>
              <th>Destaque</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$products): ?>
              <tr>
                <td colspan="7">Nenhum produto encontrado neste filtro.</td>
              </tr>
            <?php endif; ?>
            <?php foreach ($products as $product): ?>
              <tr>
                <td><img src="<?= e(image_url($product['image'] ?? null)) ?>" alt="<?= e($product['name']) ?>" class="table-thumb"></td>
                <td><?= e($product['name']) ?></td>
                <td><?= e($product['category_name']) ?></td>
                <td><?= number_format((float) $product['price'], 2, ',', '.') ?>€</td>
                <td><?= (int) $product['stock'] ?></td>
                <td><?= !empty($product['is_featured']) ? 'Sim' : 'Não' ?></td>
                <td>
                  <a href="?<?= $filter === 'featured' ? 'filter=featured&' : '' ?>edit=<?= (int) $product['id'] ?>">Editar</a>
                  <form method="post" class="inline-form">
                    <input type="hidden" name="action" value="toggle_featured">
                    <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                    <button type="submit"><?= !empty($product['is_featured']) ? 'Retirar destaque' : 'Destacar' ?></button>
                  </form>
                  <form method="post" class="inline-form" onsubmit="return confirm('Remover produto?');">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int) $product['id'] ?>">
                    <button type="submit">Apagar</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../inc/footer.php'; ?>
