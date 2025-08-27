<?php
// public/index.php

// Inclure d'abord le fichier de configuration pour définir les constantes
require_once dirname(__DIR__) . '/app/config/config.php';

// Autoloader pour charger automatiquement les classes
spl_autoload_register(function ($className) {
    // Liste des dossiers où chercher les classes
    $directories = [
        APP_PATH . '/controllers/',
        APP_PATH . '/config/models/',
        
        // Ajoute d'autres dossiers si tu as des classes utilitaires ailleurs
    ];
    

    foreach ($directories as $directory) {
        $file = $directory . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Récupération de l'URL demandée
$requestUri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Normaliser le chemin en retirant le dossier du projet (ex: lecarre_de_devs) et
// le dossier public si Apache réécrit vers public/index.php
// Cas typique MAMP: REQUEST_URI = /lecarre_de_devs/route, SCRIPT_NAME = /lecarre_de_devs/public/index.php
$scriptDir = trim(dirname($_SERVER['SCRIPT_NAME']), '/lecarre_de_devs/public');           // lecarre_de_devs/public
$baseFromScript = preg_replace('#/public$#', 'lecarre_de_devs', $scriptDir);       // lecarre_de_devs
if ($baseFromScript !== '') {
    $requestUri = preg_replace('#^' . preg_quote($baseFromScript, '#') . '/?#', '', $requestUri);
}
$requestUri = trim($requestUri, '/'); // Assurer que le chemin est propre

// Traiter les accès directs à index.php ou au répertoire public comme la racine
if ($requestUri === 'index.php' || $requestUri === 'public' || $requestUri === 'public/index.php') {
    $requestUri = '';
}

// Si l'application est servie depuis /public (sans rewrite au niveau racine), enlever un préfixe public/
if (strpos($requestUri, 'public/') === 0) {
    $requestUri = substr($requestUri, 7);
}

// Définition des routes
// Format: 'URL_PATH' => ['ControllerClassName', 'methodName', [param1, param2, ...]]
$routes = [
    '' => ['HomeController', 'index'], // Page d'accueil
    'accueil' => ['HomeController', 'index'], // Autre alias pour l'accueil

    // Authentification
    'register' => ['AuthController', 'register'],
    'login' => ['AuthController', 'login'],
    'logout' => ['AuthController', 'logout'],

    // Annonces
    'annonces' => ['AnnonceController', 'detail'], // Cette route est un peu générique, nous la rendrons plus spécifique
    'annonce/detail/(\d+)' => ['AnnonceController', 'detail'], // /annonce/detail/123
    'annonce/add' => ['AnnonceController', 'addEdit'],
    'annonce/edit/(\d+)' => ['AnnonceController', 'addEdit'], // /annonce/edit/123
    'annonce/delete/(\d+)' => ['AnnonceController', 'delete'], // /annonce/delete/123
    'my-annonces' => ['AnnonceController', 'myAnnonces'],
];

$controllerName = 'HomeController'; // Contrôleur par défaut
$methodName = 'index';             // Méthode par défaut
$params = [];                      // Paramètres à passer à la méthode

$routeFound = false;
foreach ($routes as $routePattern => $routeInfo) {
    // Pour les routes avec paramètres (regex)
    if (preg_match('#^' . $routePattern . '$#', $requestUri, $matches)) {
        $controllerName = $routeInfo[0];
        $methodName = $routeInfo[1];
        // Récupérer les paramètres de la regex (les groupes capturés)
        $params = array_slice($matches, 1);
        $routeFound = true;
        break;
    }
}

// Si la route n'est pas trouvée, ou si l'URL est vide (racine), utiliser les valeurs par défaut
if (!$routeFound && empty($requestUri)) {
    // C'est la route par défaut (accueil)
    $controllerName = 'HomeController';
    $methodName = 'index';
} elseif (!$routeFound) {
    // Gérer l'erreur 404 - Page non trouvée
    header("HTTP/1.0 404 Not Found");
    // Charger une vue d'erreur 404 si elle existe
    $errorViewFile = APP_PATH . '/views/layout/404.php';
    if (file_exists($errorViewFile)) {
        require_once APP_PATH . '/views/layout/header.php';
        require_once $errorViewFile;
        require_once APP_PATH . '/views/layout/footer.php';
    } else {
        // Vue d'erreur par défaut
        echo "<h1>404 Not Found</h1>";
        echo "<p>La page demandée n'existe pas : " . htmlspecialchars($requestUri) . "</p>";
        echo "<p><a href='" . BASE_URL . "'>Retour à l'accueil</a></p>";
    }
    exit();
}

// Instancier le contrôleur et appeler la méthode
$controllerFile = APP_PATH . '/controllers/' . $controllerName . '.php';

if (file_exists($controllerFile)) {
    // L'autoloader devrait déjà avoir chargé la classe, mais on s'assure
    // require_once $controllerFile; // Inutile si spl_autoload_register est bien configuré
    $controller = new $controllerName();
    if (method_exists($controller, $methodName)) {
        call_user_func_array([$controller, $methodName], $params);
    } else {
        header("HTTP/1.0 404 Not Found");
        echo "<h1>404 Not Found</h1>";
        echo "<p>Méthode '$methodName' non trouvée dans le contrôleur '$controllerName'.</p>";
        exit();
    }
} else {
    header("HTTP/1.0 404 Not Found");
    echo "<h1>404 Not Found</h1>";
    echo "<p>Contrôleur '$controllerName' non trouvé.</p>";
    exit();
}