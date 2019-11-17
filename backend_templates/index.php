<?php
$config = json_decode(file_get_contents('site.json'), true);
$boards = $config['boards'];
$internal_boards = $config['internal_boards'];
$articles = $config['articles'];
$themes = $config['themes'];
$site_title = $config['site_title'];
$title = $config['title'];
$is_board = false;
$is_thread = false;
$shortname = null;
$is_archive = true;
$thread_num = null;
$is_gallery = false;
$not_found = false;
$parts = explode('/', $_SERVER['REQUEST_URI']);
if (count($parts) > 2) {
    $is_board = true;
    $shortname = $parts[1];
    if (isset($internal_boards[$shortname])) {
        $is_archive = false;
    }
    if (!isset($internal_boards[$shortname]) && !isset($boards[$shortname])) {
        $not_found = true;
        $is_board = false;
    }

    if (count($parts) == 5 && $parts[1] == '_' && $parts[2] == 'theme' && array_key_exists($parts[3], $config['themes'])) {
        if ($_COOKIE['theme'] !== explode('-',  $parts[3])[0] || $_COOKIE['skin'] !== explode('-',  $parts[3])[1]) {
            list($theme, $skin) = explode('-', $parts[3]);
            setcookie('theme', $theme, time() + 3600, '/', $config['javascript_site'], 0);
            setcookie('skin', $skin, time() + 3600, '/', $config['javascript_site'], 0);
            header('Refresh:0; url=' . $_SERVER['HTTP_REFERER']);
            die();
        } else {
            header('Refresh:0; url=' . $_SERVER['HTTP_REFERER']);
            die();
        }
    }

    if (count($parts) >= 3 && $parts[2] == 'thread' && ctype_digit((string)$parts[3])) {
        $is_thread = true;
        $thread_num = $parts[3];
    }

    if (count($parts) >= 2 && $parts[2] == 'gallery') {
        $is_gallery = true;
    }
    if (!$not_found) {
        $title = '/' . htmlentities($shortname) . '/ - ' . (isset($config['boards'][$shortname]) ? htmlentities($config['boards'][$shortname]) : (isset($config['internal_boards'][$shortname]) ? htmlentities($config['internal_boards'][$shortname]) : ''));
    }
}
if (!isset($_COOKIE['theme'])) {
    setcookie('theme', 'foolfuuka', time() + 3600, '/', $config['javascript_site'], 0);
    setcookie('skin', 'default', time() + 3600, '/', $config['javascript_site'], 0);
    header("Refresh:0");
    die();
}
$theme = (isset($_COOKIE['theme']) ? $_COOKIE['theme']: '');
$skin = (isset($_COOKIE['skin']) ? $_COOKIE['skin']: '');
if (isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'foolfuuka') {
    include 'templates/foolfuuka.php';
} else {
    include 'templates/yotsuba.php';
}