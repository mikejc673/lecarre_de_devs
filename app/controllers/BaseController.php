<?php
// app/controllers/BaseController.php

class BaseController {

    public function __construct() {
        // Démarrer la session automatiquement pour tous les contrôleurs
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected function loadView($viewPath, $data = []) {
        // Chemin complet vers le fichier de vue
        // Les vues sont dans app/views/ et peuvent être dans des sous-dossiers
        $viewFile = APP_PATH . '/views/' . $viewPath . '.php';

        // S'assurer que le fichier de vue existe
        if (file_exists($viewFile)) {
            // Extrait les données passées pour les rendre disponibles comme des variables dans la vue
            extract($data);

            // Inclut l'en-tête commun
            require_once APP_PATH . '/views/layout/header.php';

            // Inclut la vue spécifique
            require_once $viewFile;

            // Inclut le pied de page commun
            require_once APP_PATH . '/views/layout/footer.php';
        } else {
            // Gérer le cas où la vue n'existe pas (ex: page 404)
            die("La vue '$viewPath' n'a pas été trouvée dans le fichier: $viewFile");
        }
    }

    protected function redirect($path) {
        // Préfixer par BASE_URL si le chemin est relatif
        if (strpos($path, 'http://') !== 0 && strpos($path, 'https://') !== 0 && strpos($path, '/') !== 0) {
            $path = BASE_URL . ltrim($path, '/');
        }
        // Chemins absolus site (/route) -> les normaliser avec BASE_URL si nécessaire
        if (strpos($path, '/') === 0 && BASE_URL !== '/') {
            $path = rtrim(BASE_URL, '/') . $path;
        }
        header('Location: ' . $path);
        exit();
    }
}