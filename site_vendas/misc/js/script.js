
document.addEventListener('DOMContentLoaded', function () {
  const menu = document.querySelector('.submenu-conta');
  if (menu) {
    const dropdown = menu.querySelector('.dropdown');
    let timeout;
    menu.addEventListener('mouseenter', () => { clearTimeout(timeout); if (dropdown) dropdown.style.display = 'block'; });
    menu.addEventListener('mouseleave', () => { timeout = setTimeout(() => { if (dropdown) dropdown.style.display = 'none'; }, 400); });
  }

  const selectableServices = Array.from(document.querySelectorAll('.selectable-service'));
  const quoteDrawer = document.getElementById('quoteDrawer');
  const closeQuoteDrawer = document.getElementById('closeQuoteDrawer');
  const serviceIdField = document.getElementById('serviceIdField');
  const serviceNameField = document.getElementById('serviceNameField');
  const serviceNameVisible = document.getElementById('serviceNameVisible');
  const selectedServiceTitle = document.getElementById('selectedServiceTitle');
  const sendWhatsappBtn = document.getElementById('sendWhatsappBtn');
  const sendEmailBtn = document.getElementById('sendEmailBtn');
  const quoteName = document.getElementById('quoteName');
  const quoteEmail = document.getElementById('quoteEmail');
  const quoteMessage = document.getElementById('quoteMessage');

  function setSelectedService(card) {
    selectableServices.forEach((item) => item.classList.remove('is-selected'));
    if (card) card.classList.add('is-selected');
  }

  function openQuoteDrawerByValues(serviceId, serviceName, card) {
    if (!quoteDrawer) return;
    setSelectedService(card || null);
    quoteDrawer.hidden = false;
    quoteDrawer.classList.add('is-open');
    quoteDrawer.setAttribute('aria-hidden', 'false');
    document.body.classList.add('quote-modal-open');
    if (serviceIdField) serviceIdField.value = serviceId || '';
    if (serviceNameField) serviceNameField.value = serviceName || '';
    if (serviceNameVisible) {
      serviceNameVisible.innerHTML = '';
      const option = document.createElement('option');
      option.value = serviceName || '';
      option.textContent = serviceName || 'Selecione um serviço';
      option.selected = true;
      serviceNameVisible.appendChild(option);
    }
    if (selectedServiceTitle) selectedServiceTitle.textContent = serviceName ? `Solicitar orçamento · ${serviceName}` : 'Solicitar orçamento';
  }

  function closeDrawer() {
    if (!quoteDrawer) return;
    quoteDrawer.classList.remove('is-open');
    quoteDrawer.setAttribute('aria-hidden', 'true');
    quoteDrawer.hidden = true;
    document.body.classList.remove('quote-modal-open');
    selectableServices.forEach((item) => item.classList.remove('is-selected'));
  }

  window.openServiceQuote = function(card) {
    if (!card) return;
    const serviceId = card.getAttribute('data-service-id') || '';
    const serviceName = card.getAttribute('data-service-name') || '';
    openQuoteDrawerByValues(serviceId, serviceName, card);
  };

  selectableServices.forEach((card) => {
    card.addEventListener('click', function (event) {
      if (event.target.closest('.btn-link-service')) return;
      window.openServiceQuote(card);
    });
    card.addEventListener('keydown', function (event) {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault();
        window.openServiceQuote(card);
      }
    });
  });

  if (closeQuoteDrawer) closeQuoteDrawer.addEventListener('click', closeDrawer);
  if (quoteDrawer) {
    quoteDrawer.addEventListener('click', function (event) {
      if (event.target === quoteDrawer || event.target.dataset.closeQuote === 'true') closeDrawer();
    });
  }
  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && quoteDrawer && quoteDrawer.classList.contains('is-open')) closeDrawer();
  });

  if (sendWhatsappBtn) {
    sendWhatsappBtn.addEventListener('click', function () {
      const nome = quoteName ? quoteName.value.trim() : '';
      const email = quoteEmail ? quoteEmail.value.trim() : '';
      const servico = serviceNameVisible ? serviceNameVisible.value.trim() : '';
      const mensagem = quoteMessage ? quoteMessage.value.trim() : '';
      if (!nome || !email || !servico || !mensagem) {
        alert('Preencha nome, email, serviço e descrição antes de enviar por WhatsApp.');
        return;
      }
      const texto = ['Olá, quero pedir um orçamento.', '', `Nome: ${nome}`, `Email: ${email}`, `Serviço: ${servico}`, `Pedido: ${mensagem}`].join('\n');
      window.open(`https://wa.me/351918689962?text=${encodeURIComponent(texto)}`, '_blank');
    });
  }

  if (sendEmailBtn) {
    sendEmailBtn.addEventListener('click', function () {
      const nome = quoteName ? quoteName.value.trim() : '';
      const email = quoteEmail ? quoteEmail.value.trim() : '';
      const servico = serviceNameVisible ? serviceNameVisible.value.trim() : '';
      const mensagem = quoteMessage ? quoteMessage.value.trim() : '';
      if (!nome || !email || !servico || !mensagem) {
        alert('Preencha nome, email, serviço e descrição antes de enviar por e-mail.');
        return;
      }
      const subject = `Pedido de orçamento - ${servico}`;
      const body = [
        'Olá, quero pedir um orçamento.',
        '',
        `Nome: ${nome}`,
        `Email: ${email}`,
        `Serviço: ${servico}`,
        `Pedido: ${mensagem}`
      ].join('\n');
      window.location.href = `mailto:mhmarques@xpc.pt?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    });
  }
});
