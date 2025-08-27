<?php
// app/config/config.php

define('DB_HOST', 'localhost');
define('DB_NAME', 'lecarre_de_devs');
define('DB_USER', 'root'); // Remplace 'root' par ton nom d'utilisateur de base de données si différent
define('DB_PASS', 'root');     // Remplace '' par ton mot de passe de base de données si tu en as un

// Chemin racine du projet pour faciliter l'inclusion des fichiers
if (!defined('ROOT_PATH')) define('ROOT_PATH', dirname(dirname(__DIR__)));
if (!defined('APP_PATH')) define('APP_PATH', ROOT_PATH . '/app');
if (!defined('PUBLIC_PATH')) define('PUBLIC_PATH', ROOT_PATH . '/public');

// URL de base pour générer des liens corrects derrière MAMP
// Déduite dynamiquement à partir de SCRIPT_NAME: ex. /lecarre_de_devs/public/index.php
$scriptDir = isset($_SERVER['SCRIPT_NAME']) ? trim(dirname($_SERVER['SCRIPT_NAME']), '/') : '';
// Si l'application est servie directement depuis le dossier public (sans réécriture au niveau racine),
// garder le suffixe /public dans l'URL de base pour que les liens fonctionnent sans dépendre du .htaccess racine
$isServedFromPublic = (substr($scriptDir, -6) === 'public');
$projectBase = $isServedFromPublic ? $scriptDir : preg_replace('#/public$#', '', $scriptDir);
$baseUrl = '/' . trim($projectBase, '/');
if ($baseUrl === '/') {
    define('BASE_URL', '/');
} else {
    define('BASE_URL', rtrim($baseUrl, '/') . '/');
}