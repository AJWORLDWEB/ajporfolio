<?php
require_once __DIR__ . '/../app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); exit('Method not allowed'); }
verify_csrf();
logout_admin();
redirect('admin/login.php');
