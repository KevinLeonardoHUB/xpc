<?php
require_once __DIR__ . '/db.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function base_path(string $path = ''): string {
    $base = rtrim(BASE_URL, '/\\');
    if ($base === '.' || $base === '/') {
        $base = '';
    }
    return $base . ($path ? '/' . ltrim($path, '/') : '');
}

function e(?string $value): string { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function redirect_to(string $path): void { header('Location: ' . base_path($path)); exit; }
function set_flash(string $type, string $message): void { $_SESSION['flash'] = compact('type', 'message'); }
function get_flash(): ?array { $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f; }

function current_user(): ?array { $id = $_SESSION['user_id'] ?? null; return $id ? find_user_by_id((int) $id) : null; }
function is_logged_in(): bool { return current_user() !== null; }
function is_admin(): bool { $u = current_user(); return $u && $u['role'] === 'admin'; }
function require_login(): void { if (!is_logged_in()) { set_flash('error', 'Faça login para continuar.'); redirect_to('login.php'); } }
function require_admin(): void { if (!is_admin()) { set_flash('error', 'Acesso restrito ao administrador.'); redirect_to('login.php'); } }

function find_user_by_id(int $id): ?array { return find_row('users', fn($u) => (int) $u['id'] === $id); }
function find_user_by_email(string $email): ?array { $email = strtolower(trim($email)); return find_row('users', fn($u) => strtolower($u['email']) === $email); }
function create_user(string $name, string $email, string $password, string $role = 'customer'): array { return insert_row('users', ['name' => $name, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT), 'role' => $role, 'created_at' => date('Y-m-d H:i:s')]); }
function update_user_password(int $id, string $password): void { update_row('users', $id, function ($u) use ($password) { $u['password'] = password_hash($password, PASSWORD_DEFAULT); return $u; }); }
function admin_users_count(): int { return count(array_filter(all_rows('users'), fn($u) => ($u['role'] ?? 'customer') === 'admin')); }

function categories_all(): array {
    $cats = all_rows('categories');
    usort($cats, function ($a, $b) {
        $aName = trim((string) ($a['name'] ?? ''));
        $bName = trim((string) ($b['name'] ?? ''));
        $aIsOutros = strcasecmp($aName, 'Outros') === 0;
        $bIsOutros = strcasecmp($bName, 'Outros') === 0;
        if ($aIsOutros && !$bIsOutros) return 1;
        if ($bIsOutros && !$aIsOutros) return -1;
        return strcasecmp($aName, $bName);
    });
    return $cats;
}
function find_category(int $id): ?array { return find_row('categories', fn($c) => (int) $c['id'] === $id); }
function save_category(array $input): void { if (!empty($input['id'])) update_row('categories', (int) $input['id'], fn($c) => array_merge($c, ['name' => $input['name'], 'slug' => $input['slug'], 'icon' => $input['icon']])); else insert_row('categories', ['name' => $input['name'], 'slug' => $input['slug'], 'icon' => $input['icon'], 'created_at' => date('Y-m-d H:i:s')]); }

function all_products(): array { $products = all_rows('products'); usort($products, fn($a, $b) => strcmp($b['created_at'], $a['created_at'])); return $products; }
function active_products(): array { return array_values(array_filter(all_products(), fn($p) => (int) ($p['is_active'] ?? 1) === 1)); }
function featured_products(int $limit = 12): array {
    $products = active_products();
    $featured = array_values(array_filter($products, fn($p) => (int) ($p['is_featured'] ?? 0) === 1));
    $featured = array_slice($featured, 0, $limit);
    foreach ($featured as &$p) {
        $cat = find_category((int) $p['category_id']);
        $p['category_name'] = $cat['name'] ?? '';
        $p['category_slug'] = $cat['slug'] ?? '';
        $p['category_icon'] = $cat['icon'] ?? 'fa-cubes';
    }
    unset($p);
    return $featured;
}
function find_product(int $id): ?array { return find_row('products', fn($p) => (int) $p['id'] === $id); }
function featured_products_count(): int { return count(array_filter(all_rows('products'), fn($p) => (int) ($p['is_featured'] ?? 0) === 1)); }
function save_product(array $input): void {
    $payload = [
        'category_id' => (int) $input['category_id'],
        'name' => $input['name'],
        'slug' => $input['slug'],
        'description' => $input['description'],
        'price' => (float) $input['price'],
        'stock' => (int) $input['stock'],
        'image' => $input['image'] ?? '',
        'is_active' => (int) $input['is_active'],
        'is_featured' => (int) ($input['is_featured'] ?? 0),
    ];
    if (!empty($input['id'])) {
        update_row('products', (int) $input['id'], fn($p) => array_merge($p, $payload));
    } else {
        insert_row('products', $payload + ['created_at' => date('Y-m-d H:i:s')]);
    }
}
function products_by_category(): array { $cats = categories_all(); $products = active_products(); $group = []; foreach ($cats as $cat) { $group[$cat['id']] = ['id' => $cat['id'], 'name' => $cat['name'], 'slug' => $cat['slug'], 'icon' => $cat['icon'] ?? 'fa-cubes', 'products' => []]; } foreach ($products as $p) { if (isset($group[$p['category_id']])) $group[$p['category_id']]['products'][] = $p; } return array_values($group); }

function services_all(): array { $services = all_rows('services'); usort($services, fn($a, $b) => strcmp($a['name'], $b['name'])); return $services; }
function active_services(): array { return array_values(array_filter(services_all(), fn($s) => (int) ($s['is_active'] ?? 1) === 1)); }
function find_service(int $id): ?array { return find_row('services', fn($s) => (int) $s['id'] === $id); }
function save_service(array $input): void {
    $payload = ['name' => $input['name'], 'slug' => $input['slug'], 'description' => $input['description'], 'image' => $input['image'] ?? '', 'is_active' => (int) $input['is_active']];
    if (!empty($input['id'])) {
        update_row('services', (int) $input['id'], fn($s) => array_merge($s, $payload));
    } else {
        insert_row('services', $payload + ['created_at' => date('Y-m-d H:i:s')]);
    }
}

function cart_items_detailed(): array { $cart = $_SESSION['cart'] ?? []; $items = []; foreach ($cart as $id => $qty) { $p = find_product((int) $id); if ($p) { $p['quantity'] = max(1, (int) $qty); $p['subtotal'] = $p['quantity'] * (float) $p['price']; $cat = find_category((int) $p['category_id']); $p['category_name'] = $cat['name'] ?? ''; $items[] = $p; } } return $items; }
function cart_total(): float { return array_reduce(cart_items_detailed(), fn($s, $i) => $s + $i['subtotal'], 0.0); }

function create_order(array $payload, array $items): int { $data = load_store(); $orderId = next_id($data, 'orders'); $data['orders'][] = ['id' => $orderId] + $payload; foreach ($items as $item) { $itemId = next_id($data, 'order_items'); $data['order_items'][] = ['id' => $itemId, 'order_id' => $orderId, 'product_id' => $item['id'], 'product_name' => $item['name'], 'quantity' => $item['quantity'], 'unit_price' => $item['price'], 'subtotal' => $item['subtotal']]; foreach ($data['products'] as &$product) { if ((int) $product['id'] === (int) $item['id']) { $product['stock'] = max(0, (int) $product['stock'] - (int) $item['quantity']); break; } } } save_store($data); return $orderId; }
function orders_by_user(int $userId): array { $rows = filter_rows('orders', fn($o) => (int) $o['user_id'] === $userId); usort($rows, fn($a, $b) => strcmp($b['created_at'], $a['created_at'])); return $rows; }
function all_orders(): array { $rows = all_rows('orders'); usort($rows, fn($a, $b) => strcmp($b['created_at'], $a['created_at'])); return $rows; }
function update_order_status(int $id, string $status): void { update_row('orders', $id, fn($o) => array_merge($o, ['status' => $status])); }
function create_service_request(array $input): void { insert_row('service_requests', $input + ['created_at' => date('Y-m-d H:i:s')]); }

function upload_image(string $fieldName, ?string $current = null): string {
    if (empty($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
        return $current ?? '';
    }

    $file = $_FILES[$fieldName];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return $current ?? '';
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Falha no upload da imagem.');
    }

    if (($file['size'] ?? 0) > 5 * 1024 * 1024) {
        throw new RuntimeException('A imagem deve ter no máximo 5MB.');
    }

    $mime = mime_content_type($file['tmp_name']);
    $map = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
    if (!isset($map[$mime])) {
        throw new RuntimeException('Formato de imagem inválido. Use JPG, PNG, WEBP ou GIF.');
    }

    if (!is_dir(UPLOADS_DIR)) {
        mkdir(UPLOADS_DIR, 0777, true);
    }

    $filename = date('YmdHis') . '-' . bin2hex(random_bytes(6)) . '.' . $map[$mime];
    $destination = UPLOADS_DIR . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Não foi possível salvar a imagem enviada.');
    }

    if (!empty($current)) {
        delete_uploaded_file($current);
    }

    return $filename;
}

function delete_uploaded_file(?string $filename): void {
    if (empty($filename)) {
        return;
    }
    $path = UPLOADS_DIR . '/' . basename($filename);
    if (is_file($path)) {
        @unlink($path);
    }
}

function image_url(?string $filename, string $fallback = 'misc/img/logo.webp'): string {
    if (!empty($filename)) {
        return UPLOADS_URL . '/' . rawurlencode($filename);
    }
    return base_path($fallback);
}
