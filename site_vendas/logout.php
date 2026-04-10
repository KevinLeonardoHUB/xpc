<?php
require_once __DIR__ . '/inc/functions.php';
session_destroy();
session_start();
set_flash('success', 'Sessão terminada.');
redirect_to('login.php');
