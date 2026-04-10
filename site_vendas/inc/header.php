<?php require_once __DIR__ . '/functions.php'; $flash = get_flash(); $user = current_user(); ?>
<!doctype html>
<html lang="pt">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= e($pageTitle ?? APP_NAME) ?></title>
    <link rel="stylesheet" href="<?= e(base_path('misc/css/style.css')) ?>?v=20260410b" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link rel="icon" href="<?= e(base_path('misc/img/symbol_transparent.png')) ?>">
  </head>
  <body>
    <div class="topbar">
      <div class="topbar-inner">
        <div class="topbar-left">
          <a href="mailto:info@xpc.pt">info@xpc.pt</a>
          <span><i class="fa fa-street-view"></i>Rua de Entrecampos, 64 A</span>
          <a href="tel:+351216009444"><i class="fa fa-phone"></i>+351 21 600 9444</a>
        </div>
      </div>
    </div>

    <header class="bar">
      <div class="bar-container">
        <div class="logo">
          <a href="https://xpc.pt/"><img src="<?= e(base_path('misc/img/logo.webp')) ?>" alt="XPC Informática" /></a>
        </div>
        <nav class="menu">
          <a href="<?= e(base_path('index.php')) ?>#inicio">Início</a>
          <a href="<?= e(base_path('index.php')) ?>#categorias">Categorias</a>
          <a href="<?= e(base_path('index.php')) ?>#produtos">Produtos</a>
          <a href="<?= e(base_path('index.php')) ?>#servicos">Serviços</a>
          <a href="<?= e(base_path('index.php')) ?>#contactos">Contactos</a>
          <?php if ($user): ?>
            <div class="menu-item submenu-conta">
              <a href="#" class="menu-botao"><?= e(explode(' ', $user['name'])[0]) ?></a>
              <div class="dropdown">
                <?php if ($user['role'] === 'admin'): ?><a href="<?= e(base_path('admin/index.php')) ?>">Painel admin</a><?php endif; ?>
                <a href="<?= e(base_path('account.php')) ?>">Minha conta</a>
                <a href="<?= e(base_path('logout.php')) ?>">Sair</a>
              </div>
            </div>
          <?php else: ?>
            <div class="menu-item submenu-conta">
              <a href="#" class="menu-botao">Conta</a>
              <div class="dropdown">
                <a href="<?= e(base_path('login.php')) ?>">Login</a>
                <a href="<?= e(base_path('register.php')) ?>">Registro</a>
              </div>
            </div>
          <?php endif; ?>
          <div class="menu-item submenu-carrinho">
            <a href="<?= e(base_path('cart.php')) ?>" class="menu-botao menu-botao-destaque">
              Carrinho (<?= count($_SESSION['cart'] ?? []) ?>)
            </a>
          </div>
        </nav>
      </div>
    </header>
    <?php if ($flash): ?>
      <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>
