<?php
$page_title = $page_title ?? 'ระบบประเมินบุคคล';
$active_page = $active_page ?? '';
?>
<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($page_title) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="d-flex">
    <?php include 'sidebar.php'; ?>
    <main class="flex-grow-1 p-4">
      <?php include 'topbar.php'; ?>
    <h3><?= htmlspecialchars($page_title) ?></h3>