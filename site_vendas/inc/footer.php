    <footer class="footer footer-xpc" id="contactos">
      <div class="section-container footer-grid footer-grid-xpc">
        <div>
          <h4>XPC Informática</h4>
          <p>Rua de Entrecampos, 64 A</p>
          <p>Lisboa</p>
          <p><a href="mailto:info@xpc.pt">info@xpc.pt</a></p>
          <p><a href="tel:+351216009444">+351 21 600 9444</a></p>
          <p><a href="https://wa.me/351918689962" target="_blank" rel="noopener">WhatsApp: +351 91 868 9962</a></p>
        </div>
        <div>
          <h4>Links rápidos</h4>
          <p><a href="<?= e(base_path('index.php')) ?>#categorias">Categorias</a></p>
          <p><a href="<?= e(base_path('index.php')) ?>#servicos">Serviços</a></p>
          <p><a href="<?= e(base_path('index.php')) ?>#servicos">Pedir orçamento</a></p>
        </div>
        <div>
          <h4>Conta</h4>
          <?php if ($user): ?>
            <?php if ($user['role'] === 'admin'): ?><p><a href="<?= e(base_path('admin/index.php')) ?>">Painel admin</a></p><?php endif; ?>
            <p><a href="<?= e(base_path('account.php')) ?>">Minha conta</a></p>
            <p><a href="<?= e(base_path('cart.php')) ?>">Carrinho</a></p>
          <?php else: ?>
            <p><a href="<?= e(base_path('login.php')) ?>">Login</a></p>
            <p><a href="<?= e(base_path('register.php')) ?>">Registro</a></p>
            <p><a href="<?= e(base_path('cart.php')) ?>">Carrinho</a></p>
          <?php endif; ?>
        </div>
      </div>
    </footer>
    <script src="<?= e(base_path('misc/js/script.js')) ?>?v=20260410b"></script>
  </body>
</html>
