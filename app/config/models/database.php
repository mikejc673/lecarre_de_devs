<?php
// app/config/models/Database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Les constantes sont déjà définies dans config.php qui est inclus avant
        // Pas besoin de re-inclure config.php ici
        
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Active les exceptions pour les erreurs
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,     // Récupère les résultats sous forme de tableau associatif
            PDO::ATTR_EMULATE_PREPARES   => false,                 // Désactive l'émulation des requêtes préparées pour de meilleures performances et sécurité
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // En cas d'erreur de connexion, affiche un message et arrête le script
            error_log('Erreur de connexion à la base de données : ' . $e->getMessage());
            die('Impossible de se connecter à la base de données. Veuillez réessayer plus tard.');
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}