<?php
require_once __DIR__ . '/inc/functions.php';
require_once __DIR__ . '/inc/mail.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceId = (int)($_POST['service_id'] ?? 0);
    $serviceName = trim($_POST['service_name'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($serviceId > 0) {
        $service = find_service($serviceId);
        if ($service) {
            $serviceName = $service['name'];
        }
    }

    create_service_request([
        'name' => $name,
        'email' => $email,
        'service_id' => $serviceId,
        'service' => $serviceName,
        'message' => $message,
    ]);

    $subject = 'Novo pedido de orçamento - ' . ($serviceName ?: 'Serviço');
    $html = '<h2>Novo pedido de orçamento</h2>'
        . '<p><strong>Nome:</strong> ' . e($name) . '</p>'
        . '<p><strong>Email:</strong> ' . e($email) . '</p>'
        . '<p><strong>Serviço:</strong> ' . e($serviceName) . '</p>'
        . '<p><strong>Mensagem:</strong><br>' . nl2br(e($message)) . '</p>';
    $alt = "Novo pedido de orçamento\n\nNome: {$name}\nEmail: {$email}\nServiço: {$serviceName}\nMensagem: {$message}";

    @send_mail_message(ADMIN_EMAIL, APP_NAME, $subject, $html, $alt);

    set_flash('success', 'Pedido de orçamento enviado com sucesso.');
}
redirect_to('index.php#servicos');
