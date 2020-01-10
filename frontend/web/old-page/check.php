<?php

if (!isset($_COOKIE['new'])) {
    if (isset($_GET['old'])) {
        header('Location: /', true, 302);
        exit;
    }
    if ($_SERVER['REQUEST_URI'] === '/') {
        include __DIR__ . '/index.html';
    } else if (isset($_GET['new'])) {
        setcookie('new', 1, strtotime('+7 DAYS'));
        header('Location: /', true, 302);
        exit;
    } else {
        header("HTTP/1.0 404 Not Found");
        echo 'Page not found';
    }
    exit;
} else if (isset($_GET['old'])) {
    setcookie('new', 1, strtotime('-7 DAYS'));
    header('Location: /', true, 302);
    exit;
} else if (isset($_GET['new'])) {
    header('Location: /', true, 302);
}