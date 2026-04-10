<?php
require_once __DIR__ . '/config.php';

function store_path(): string { return __DIR__ . '/../storage/database.json'; }

function store_seed_data(): array {
    $now = date('Y-m-d H:i:s');
    $categories = []; $products = []; $users = []; $services = [];
    $users[] = ['id' => 1, 'name' => 'Administrador', 'email' => ADMIN_EMAIL, 'password' => password_hash(ADMIN_DEFAULT_PASSWORD, PASSWORD_DEFAULT), 'role' => 'admin', 'created_at' => $now];
    $catData = [
        ['name' => 'Componentes', 'icon' => 'fa-microchip'],
        ['name' => 'Recondicionados', 'icon' => 'fa-refresh'],
        ['name' => 'Consumíveis', 'icon' => 'fa-tint'],
        ['name' => 'Servidores', 'icon' => 'fa-server'],
        ['name' => 'Acessórios', 'icon' => 'fa-keyboard-o'],
        ['name' => 'Armazenamento', 'icon' => 'fa-hdd-o'],
        ['name' => 'Cabos e adaptadores', 'icon' => 'fa-plug'],
        ['name' => 'Redes', 'icon' => 'fa-sitemap'],
        ['name' => 'Outros', 'icon' => 'fa-cubes'],
    ];
    $serviceData = [
        ['name' => 'Diagnóstico técnico', 'slug' => 'diagnostico-tecnico', 'description' => 'Análise de falhas e identificação de problemas em equipamentos e sistemas.', 'image' => '', 'is_active' => 1],
        ['name' => 'Reparação de computadores', 'slug' => 'reparacao-de-computadores', 'description' => 'Assistência técnica para desktops e portáteis com avaliação e orçamento personalizado.', 'image' => '', 'is_active' => 1],
        ['name' => 'Otimização de sistemas', 'slug' => 'otimizacao-de-sistemas', 'description' => 'Melhoria de desempenho, configuração e atualização para uso pessoal ou profissional.', 'image' => '', 'is_active' => 1],
        ['name' => 'Servidores e recuperação', 'slug' => 'servidores-e-recuperacao', 'description' => 'Suporte técnico, avaliação e recuperação de falhas em ambientes mais exigentes.', 'image' => '', 'is_active' => 1],
    ];
    $catId = 1; $prodId = 1; $serviceId = 1;
    foreach ($catData as $catInfo) {
        $name = $catInfo['name'];
        $slug = slugify($name);
        $categories[] = ['id' => $catId, 'name' => $name, 'slug' => $slug, 'icon' => $catInfo['icon'], 'created_at' => $now];
        for ($i = 1; $i <= 3; $i++) {
            $products[] = ['id' => $prodId++, 'category_id' => $catId, 'name' => 'Produto teste ' . $i . ' - ' . $name, 'slug' => slugify('produto-teste-' . $i . '-' . $name . '-' . $prodId), 'description' => 'Produto de demonstração para a categoria ' . $name . '.', 'price' => rand(9, 199) + 0.90, 'stock' => rand(5, 30), 'image' => '', 'is_active' => 1, 'is_featured' => $i === 1 ? 1 : 0, 'created_at' => $now];
        }
        $catId++;
    }
    foreach ($serviceData as $service) {
        $services[] = ['id' => $serviceId++, 'created_at' => $now] + $service;
    }
    return ['users' => $users, 'categories' => $categories, 'products' => $products, 'services' => $services, 'orders' => [], 'order_items' => [], 'password_resets' => [], 'service_requests' => [], 'counters' => ['users' => 2, 'categories' => $catId, 'products' => $prodId, 'services' => $serviceId, 'orders' => 1, 'order_items' => 1, 'password_resets' => 1, 'service_requests' => 1]];
}

function normalize_store(array $data): array {
    $iconMap = [
        'servicos-reparacao' => 'fa-wrench',
        'componentes' => 'fa-cogs',
        'recondicionados' => 'fa-laptop',
        'consumiveis' => 'fa-print',
        'servidores' => 'fa-server',
        'acessorios' => 'fa-keyboard-o',
        'armazenamento' => 'fa-hdd-o',
        'cabos-e-adaptadores' => 'fa-plug',
        'redes' => 'fa-wifi',
        'outros' => 'fa-ellipsis-h',
    ];

    $data['categories'] = array_map(function ($category) use ($iconMap) {
        $slug = $category['slug'] ?? slugify($category['name'] ?? 'categoria');
        $category['slug'] = $slug;
        $category['icon'] = $category['icon'] ?? ($iconMap[$slug] ?? 'fa-cubes');
        return $category;
    }, $data['categories'] ?? []);

    $data['products'] = array_map(function ($product) {
        $product['image'] = $product['image'] ?? '';
        $product['is_active'] = isset($product['is_active']) ? (int) $product['is_active'] : 1;
        $product['is_featured'] = isset($product['is_featured']) ? (int) $product['is_featured'] : 0;
        return $product;
    }, $data['products'] ?? []);

    if (!isset($data['services']) || !is_array($data['services'])) {
        $now = date('Y-m-d H:i:s');
        $data['services'] = [
            ['id' => 1, 'name' => 'Diagnóstico técnico', 'slug' => 'diagnostico-tecnico', 'description' => 'Análise de falhas e identificação de problemas em equipamentos e sistemas.', 'image' => '', 'is_active' => 1, 'created_at' => $now],
            ['id' => 2, 'name' => 'Reparação de computadores', 'slug' => 'reparacao-de-computadores', 'description' => 'Assistência técnica para desktops e portáteis com avaliação e orçamento personalizado.', 'image' => '', 'is_active' => 1, 'created_at' => $now],
            ['id' => 3, 'name' => 'Otimização de sistemas', 'slug' => 'otimizacao-de-sistemas', 'description' => 'Melhoria de desempenho, configuração e atualização para uso pessoal ou profissional.', 'image' => '', 'is_active' => 1, 'created_at' => $now],
            ['id' => 4, 'name' => 'Servidores e recuperação', 'slug' => 'servidores-e-recuperacao', 'description' => 'Suporte técnico, avaliação e recuperação de falhas em ambientes mais exigentes.', 'image' => '', 'is_active' => 1, 'created_at' => $now],
        ];
    } else {
        $data['services'] = array_map(function ($service) {
            $service['image'] = $service['image'] ?? '';
            $service['is_active'] = isset($service['is_active']) ? (int) $service['is_active'] : 1;
            $service['slug'] = $service['slug'] ?? slugify($service['name'] ?? 'servico');
            $service['description'] = $service['description'] ?? '';
            return $service;
        }, $data['services']);
    }

    $data['service_requests'] = array_map(function ($request) {
        $request['service_id'] = $request['service_id'] ?? 0;
        return $request;
    }, $data['service_requests'] ?? []);

    $data['counters'] = $data['counters'] ?? [];
    foreach (['users', 'categories', 'products', 'services', 'orders', 'order_items', 'password_resets', 'service_requests'] as $table) {
        if (!isset($data['counters'][$table])) {
            $maxId = 0;
            foreach (($data[$table] ?? []) as $row) {
                $maxId = max($maxId, (int) ($row['id'] ?? 0));
            }
            $data['counters'][$table] = $maxId + 1;
        }
    }

    return $data;
}

function load_store(): array {
    $path = store_path();
    if (!file_exists($path)) {
        if (!is_dir(dirname($path))) mkdir(dirname($path), 0777, true);
        $data = store_seed_data();
        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $data;
    }
    $data = json_decode(file_get_contents($path), true);
    if (!is_array($data)) { $data = store_seed_data(); }
    $data = normalize_store($data);
    save_store($data);
    return $data;
}

function save_store(array $data): void { file_put_contents(store_path(), json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); }

function next_id(array &$data, string $table): int { $id = $data['counters'][$table] ?? 1; $data['counters'][$table] = $id + 1; return $id; }

function all_rows(string $table): array { $data = load_store(); return $data[$table] ?? []; }
function find_row(string $table, callable $fn): ?array { foreach (all_rows($table) as $row) { if ($fn($row)) return $row; } return null; }
function filter_rows(string $table, callable $fn): array { return array_values(array_filter(all_rows($table), $fn)); }
function insert_row(string $table, array $row): array { $data = load_store(); $row['id'] = next_id($data, $table); $data[$table][] = $row; save_store($data); return $row; }
function update_row(string $table, int $id, callable $updater): ?array { $data = load_store(); foreach ($data[$table] as &$row) { if ((int) $row['id'] === $id) { $row = $updater($row); save_store($data); return $row; } } return null; }
function delete_row(string $table, int $id): void { $data = load_store(); $data[$table] = array_values(array_filter($data[$table], fn($r) => (int) $r['id'] !== $id)); if ($table === 'categories') { $data['products'] = array_values(array_filter($data['products'], fn($r) => (int) $r['category_id'] !== $id)); } if ($table === 'products') { $data['order_items'] = array_values(array_filter($data['order_items'], fn($r) => (int) $r['product_id'] !== $id)); } if ($table === 'services') { $data['service_requests'] = array_values(array_filter($data['service_requests'], fn($r) => (int) ($r['service_id'] ?? 0) !== $id)); } save_store($data); }

function slugify(string $text): string { $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text); $text = preg_replace('/[^a-zA-Z0-9]+/', '-', $text); return trim(strtolower($text ?? ''), '-') ?: 'item'; }
