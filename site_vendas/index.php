<?php
require_once __DIR__ . '/inc/functions.php';
$grouped = products_by_category();
$featured = featured_products(9);
$services = active_services();
$pageTitle = 'XPC Informática';
include __DIR__ . '/inc/header.php';
?>
<main>
  <section class="hero" id="inicio">
    <div class="hero-container">
      <div class="hero-text">
        <span class="hero-tag">XPC Informática em Entrecampos</span>
        <h1>Serviços técnicos, produtos e soluções de informática</h1>
        <p>A XPC Informática disponibiliza serviços de reparação, componentes, equipamentos recondicionados, consumíveis e outros produtos para clientes particulares e empresas. Peça o seu orçamento de forma simples e rápida.</p>
        <div class="hero-buttons">
          <a href="#servicos" class="btn-primary">Pedir orçamento</a>
          <a href="#categorias" class="btn-secondary">Ver categorias</a>
        </div>
      </div>
    </div>
  </section>

  <section class="section categorias-section" id="categorias">
    <div class="section-container">
      <div class="section-header categories-header">
        <span class="section-subtitle">Explore as nossas categorias</span>
        <h2>Peça o seu orçamento!</h2>
        <p>Organizámos os principais serviços e produtos em categorias para tornar o pedido de orçamento mais rápido e simples.</p>
      </div>
      <div class="categorias-grid xpc-categorias-grid">
        <?php foreach ($grouped as $category): ?>
          <a href="#cat-<?= e($category['slug']) ?>" class="categoria-item xpc-categoria-item" aria-label="Ir para a categoria <?= e($category['name']) ?>">
            <div class="categoria-icon xpc-categoria-icon"><i class="fa <?= e($category['icon'] ?? 'fa-cubes') ?>"></i></div>
            <span><?= e($category['name']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="section section-light produtos-section" id="produtos">
    <div class="section-container">
      <div class="section-header">
        <span class="section-subtitle">Loja</span>
        <h2>Produtos em destaque</h2>
        <p>Adicione produtos ao carrinho e finalize a compra no checkout interno.</p>
      </div>
      <div class="products-grid">
        <?php if (!$featured): ?>
          <article class="product-card">
            <h4>Sem produtos em destaque</h4>
            <p>O administrador ainda não marcou nenhum produto como destaque.</p>
          </article>
        <?php else: ?>
          <?php foreach ($featured as $product): ?>
            <article class="product-card">
              <div class="product-media"><img src="<?= e(image_url($product['image'] ?? null)) ?>" alt="<?= e($product['name']) ?>" class="product-cover"></div>
              <span class="product-badge"><i class="fa <?= e($product['category_icon'] ?? 'fa-cubes') ?>"></i> <?= e($product['category_name']) ?></span>
              <h4><?= e($product['name']) ?></h4>
              <p><?= e($product['description']) ?></p>
              <div class="product-meta"><strong><?= number_format((float)$product['price'], 2, ',', '.') ?>€</strong><span>Stock: <?= (int)$product['stock'] ?></span></div>
              <form method="post" action="<?= e(base_path('cart.php')) ?>" class="inline-form">
                <input type="hidden" name="action" value="add">
                <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                <button type="submit">Comprar</button>
              </form>
            </article>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>

      <?php foreach ($grouped as $category): ?>
        <div class="product-category" id="cat-<?= e($category['slug']) ?>">
          <div class="product-category-header"><h3><i class="fa <?= e($category['icon'] ?? 'fa-cubes') ?>"></i> <?= e($category['name']) ?></h3><a href="#categorias">Voltar às categorias</a></div>
          <div class="products-grid">
            <?php if (!$category['products']): ?>
              <article class="product-card"><h4>Sem produtos</h4><p>O administrador pode adicionar produtos no painel.</p></article>
            <?php else: foreach ($category['products'] as $product): ?>
              <article class="product-card">
                <img src="<?= e(image_url($product['image'] ?? null)) ?>" alt="<?= e($product['name']) ?>" class="product-cover">
                <span class="product-badge"><i class="fa <?= e($category['icon'] ?? 'fa-cubes') ?>"></i> <?= e($category['name']) ?></span>
                <h4><?= e($product['name']) ?></h4>
                <p><?= e($product['description']) ?></p>
                <div class="product-meta"><strong><?= number_format((float)$product['price'], 2, ',', '.') ?>€</strong><span>Stock: <?= (int)$product['stock'] ?></span></div>
                <form method="post" action="<?= e(base_path('cart.php')) ?>" class="inline-form">
                  <input type="hidden" name="action" value="add">
                  <input type="hidden" name="product_id" value="<?= (int)$product['id'] ?>">
                  <button type="submit">Comprar</button>
                </form>
              </article>
            <?php endforeach; endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="section services-xpc-section" id="servicos">
    <div class="section-container">
      <div class="section-header services-header">
        <span class="section-subtitle">Assistência técnica</span>
        <h2>Soluções especializadas em reparação e suporte</h2>
        <p>A nossa equipa presta apoio técnico especializado com diagnóstico, reparação, otimização e recuperação de sistemas.</p>
      </div>
      <div class="service-grid services-xpc-grid">
        <?php foreach ($services as $service): ?>
          <article class="service-card selectable-service xpc-service-card" tabindex="0" role="button" aria-label="Selecionar serviço <?= e($service['name']) ?>" data-service-id="<?= (int)$service['id'] ?>" data-service-name="<?= e($service['name']) ?>" data-service-description="<?= e($service['description']) ?>" onclick="window.openServiceQuote && window.openServiceQuote(this)" onkeydown="if(event.key==='Enter'||event.key===' '){event.preventDefault();window.openServiceQuote && window.openServiceQuote(this);}">
            <div class="service-cover-wrap">
              <img class="service-cover" src="<?= e(image_url($service['image'] ?? null, 'misc/img/logo.webp')) ?>" alt="<?= e($service['name']) ?>">
            </div>
            <div class="service-card-body xpc-service-body">
              <h3><?= e($service['name']) ?></h3>
              <p><?= e($service['description']) ?></p>
              <button type="button" class="service-select-btn btn-link-service" onclick="event.stopPropagation();window.openServiceQuote && window.openServiceQuote(this.closest('.xpc-service-card'))">Peça o orçamento</button>
            </div>
          </article>
        <?php endforeach; ?>
      </div>

      <div class="quote-modal" id="quoteDrawer" aria-hidden="true" hidden>
        <div class="quote-modal-backdrop" data-close-quote="true"></div>
        <div class="quote-modal-card" role="dialog" aria-modal="true" aria-labelledby="selectedServiceTitle">
          <button type="button" class="quote-modal-close" id="closeQuoteDrawer" aria-label="Fechar orçamento">&times;</button>
          <div class="quote-modal-header">
            <div>
              <span class="section-subtitle">Pedido de orçamento</span>
              <h3 id="selectedServiceTitle">Solicitar orçamento</h3>
            </div>
          </div>
          <p class="quote-panel-intro">Preencha os dados abaixo para pedir orçamento de produtos, reparações, servidores ou outros serviços.</p>
          <form method="post" action="<?= e(base_path('service_request.php')) ?>" class="auth-card quote-modal-form" id="serviceQuoteForm">
            <input type="hidden" name="service_id" id="serviceIdField" value="">
            <input type="hidden" name="service_name" id="serviceNameField" value="">
            <div class="form-grid form-grid-xpc">
              <div class="field-group field-group-full">
                <label for="serviceNameVisible">Categoria</label>
                <select id="serviceNameVisible" disabled>
                  <option value="">Selecione um serviço</option>
                </select>
              </div>
              <div class="field-group field-group-full">
                <label for="quoteName">Nome</label>
                <input type="text" name="name" id="quoteName" placeholder="Digite o seu nome" required>
              </div>
              <div class="field-group field-group-full">
                <label for="quoteEmail">Email</label>
                <input type="email" name="email" id="quoteEmail" placeholder="Digite o seu email" required>
              </div>
              <div class="field-group field-group-full">
                <label for="quoteMessage">Descrição do pedido / erro</label>
                <textarea name="message" id="quoteMessage" rows="6" placeholder="Descreva o que precisa, o produto pretendido ou o erro do equipamento..." required></textarea>
              </div>
            </div>
            <div class="quote-actions">
              <button type="button" class="btn-primary" id="sendEmailBtn">Enviar por e-mail</button>
              <button type="button" class="btn-secondary" id="sendWhatsappBtn">Enviar por WhatsApp</button>
            </div>
          </form>
        </div>
      </div>
  </div>
</section>
</main>
<?php include __DIR__ . '/inc/footer.php'; ?>
