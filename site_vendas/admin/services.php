<?php
require_once __DIR__ . '/../inc/functions.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $existing = $id ? find_service($id) : null;
        try {
            $image = upload_image('image', $existing['image'] ?? null);
            save_service([
                'id' => $id,
                'name' => trim($_POST['name'] ?? ''),
                'slug' => trim($_POST['slug'] ?? '') ?: slugify($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'image' => $image,
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
            ]);
            set_flash('success', !empty($_POST['id']) ? 'Serviço atualizado.' : 'Serviço criado.');
        } catch (RuntimeException $e) {
            set_flash('error', $e->getMessage());
        }
        redirect_to('admin/services.php');
    }
    if ($action === 'delete') {
        $service = find_service((int)$_POST['id']);
        if ($service) {
            delete_uploaded_file($service['image'] ?? null);
        }
        delete_row('services', (int)$_POST['id']);
        set_flash('success', 'Serviço removido.');
        redirect_to('admin/services.php');
    }
}
$edit = !empty($_GET['edit']) ? find_service((int)$_GET['edit']) : null;
$services = services_all();
$pageTitle = 'Gerir serviços';
include __DIR__ . '/../inc/header.php';
?>
<div class="page-wrap"><div class="section-container"><div class="section-header"><h2>Gerir serviços</h2><p>Escolha imagem, descrição e estado ativo para cada serviço.</p></div><div class="checkout-grid"><form method="post" enctype="multipart/form-data" class="auth-card"><h3><?= $edit ? 'Editar serviço' : 'Novo serviço' ?></h3><input type="hidden" name="action" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>"><input type="text" name="name" placeholder="Nome do serviço" value="<?= e($edit['name'] ?? '') ?>" required><input type="text" name="slug" placeholder="Slug" value="<?= e($edit['slug'] ?? '') ?>"><textarea name="description" rows="4" placeholder="Descrição do serviço" required><?= e($edit['description'] ?? '') ?></textarea><label class="upload-field">Imagem do serviço<input type="file" name="image" accept="image/*"></label><?php if (!empty($edit['image'])): ?><img src="<?= e(image_url($edit['image'])) ?>" alt="Imagem do serviço" class="admin-thumb"><?php endif; ?><label class="checkline"><input type="checkbox" name="is_active" <?= !isset($edit['is_active']) || $edit['is_active'] ? 'checked' : '' ?>> Serviço ativo</label><button type="submit" class="btn-primary">Guardar</button></form><div class="table-card"><table class="data-table"><thead><tr><th>Imagem</th><th>Serviço</th><th>Descrição</th><th>Estado</th><th></th></tr></thead><tbody><?php foreach($services as $service): ?><tr><td><img src="<?= e(image_url($service['image'] ?? null)) ?>" alt="<?= e($service['name']) ?>" class="table-thumb"></td><td><?= e($service['name']) ?></td><td><?= e($service['description']) ?></td><td><?= !empty($service['is_active']) ? 'Ativo' : 'Inativo' ?></td><td><a href="?edit=<?= (int)$service['id'] ?>">Editar</a> <form method="post" class="inline-form" onsubmit="return confirm('Remover serviço?');"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$service['id'] ?>"><button type="submit">Apagar</button></form></td></tr><?php endforeach; ?></tbody></table></div></div></div></div>
<?php include __DIR__ . '/../inc/footer.php'; ?>
