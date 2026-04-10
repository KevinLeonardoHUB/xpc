<?php
require_once __DIR__ . '/inc/functions.php';
set_flash('error', 'A recuperação de palavra-passe foi removida deste projeto.');
redirect_to('login.php');
