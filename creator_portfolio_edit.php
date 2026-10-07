<?php
// creator_portfolio_edit.php - Dedicated alias redirect to creator_portfolio_add.php edit mode
require_once __DIR__ . '/config/db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_GET['edit']) ? (int)$_GET['edit'] : 0);
if ($id > 0) {
    header("Location: creator_portfolio_add.php?edit=" . $id);
    exit;
}
header("Location: creator_portfolio.php");
exit;
