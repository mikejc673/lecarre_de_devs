<?php
// app/models/UserModel.php

require_once APP_PATH . '/config/models/database.php';

class UserModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Enregistre un nouvel utilisateur
    public function registerUser($email, $password_hashed) {
        try {
            $query = "INSERT INTO utilisateurs (email, mot_de_passe) VALUES (:email, :password_hashed)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password_hashed', $password_hashed, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Gérer le cas où l'email est déjà utilisé (erreur de clé unique)
            if ($e->getCode() == 23000) { // Code d'erreur SQL pour violation de contrainte d'unicité
                return false; // Indique que l'email est déjà pris
            }
            error_log('Erreur lors de l\'inscription : ' . $e->getMessage());
            return false;
        }
    }

    // Trouve un utilisateur par son email
    public function getUserByEmail($email) {
        try {
            $query = "SELECT * FROM utilisateurs WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    // Trouve un utilisateur par son ID
    public function getUserById($id_utilisateur) {
        try {
            $query = "SELECT * FROM utilisateurs WHERE id_utilisateur = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id_utilisateur, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage());
            return false;
        }
    }

    // Vérifie si un email existe déjà
    public function emailExists($email) {
        try {
            $query = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            error_log('Erreur lors de la vérification de l\'email : ' . $e->getMessage());
            return false;
        }
    }
}