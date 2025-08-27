<?php
// app/models/CategoryModel.php

require_once APP_PATH . '/config/models/database.php';

class CategoryModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Récupère toutes les catégories
    public function getAllCategories() {
        try {
            $query = "SELECT * FROM categories ORDER BY nom_categorie ASC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des catégories : ' . $e->getMessage());
            return [];
        }
    }

    // Récupère une catégorie par son ID
    public function getCategoryById($id_categorie) {
        try {
            $query = "SELECT * FROM categories WHERE id_categorie = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id_categorie, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de la catégorie : ' . $e->getMessage());
            return false;
        }
    }

    // Ajoute une nouvelle catégorie (pour l'administration, si nécessaire)
    public function addCategory($nom_categorie) {
        try {
            $query = "INSERT INTO categories (nom_categorie) VALUES (:nom)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom_categorie, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de l\'ajout de la catégorie : ' . $e->getMessage());
            return false;
        }
    }

    // Met à jour une catégorie (pour l'administration, si nécessaire)
    public function updateCategory($id_categorie, $nom_categorie) {
        try {
            $query = "UPDATE categories SET nom_categorie = :nom WHERE id_categorie = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':nom', $nom_categorie, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id_categorie, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la mise à jour de la catégorie : ' . $e->getMessage());
            return false;
        }
    }

    // Supprime une catégorie (avec précaution, car lié aux annonces)
    public function deleteCategory($id_categorie) {
        try {
            $query = "DELETE FROM categories WHERE id_categorie = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id_categorie, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de la catégorie : ' . $e->getMessage());
            return false;
        }
    }
}