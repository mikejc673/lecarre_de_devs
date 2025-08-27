<?php
// app/models/AnnonceModel.php

require_once APP_PATH . '/config/models/database.php';

class AnnonceModel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Récupère toutes les annonces (pour l'accueil)
    public function getAllAnnonces() {
        try {
            $query = "SELECT a.*, u.email as user_email, c.nom_categorie
                      FROM annonces a
                      JOIN utilisateurs u ON a.id_utilisateur = u.id_utilisateur
                      JOIN categories c ON a.id_categorie = c.id_categorie
                      ORDER BY a.date_publication DESC";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des annonces : ' . $e->getMessage());
            return [];
        }
    }

    // Récupère une annonce par son ID
    public function getAnnonceById($id_annonce) {
        try {
            $query = "SELECT a.*, u.email as user_email, c.nom_categorie
                      FROM annonces a
                      JOIN utilisateurs u ON a.id_utilisateur = u.id_utilisateur
                      JOIN categories c ON a.id_categorie = c.id_categorie
                      WHERE a.id_annonce = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id_annonce, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }

    // Récupère les annonces d'un utilisateur spécifique
    public function getUserAnnonces($id_utilisateur) {
        try {
            $query = "SELECT a.*, c.nom_categorie
                      FROM annonces a
                      JOIN categories c ON a.id_categorie = c.id_categorie
                      WHERE a.id_utilisateur = :user_id
                      ORDER BY a.date_publication DESC";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $id_utilisateur, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log('Erreur lors de la récupération des annonces utilisateur : ' . $e->getMessage());
            return [];
        }
    }

    // Ajoute une nouvelle annonce [cite: 29]
    public function addAnnonce($titre, $description, $prix, $id_categorie, $id_utilisateur) {
        try {
            $query = "INSERT INTO annonces (titre, description, prix, id_categorie, id_utilisateur, date_publication)
                      VALUES (:titre, :description, :prix, :id_categorie, :id_utilisateur, NOW())";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':prix', $prix, PDO::PARAM_STR); // Utiliser STR pour DECIMAL
            $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de l\'ajout de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }

    // Met à jour une annonce [cite: 30]
    public function updateAnnonce($id_annonce, $titre, $description, $prix, $id_categorie, $id_utilisateur) {
        try {
            $query = "UPDATE annonces
                      SET titre = :titre, description = :description, prix = :prix, id_categorie = :id_categorie, date_publication = NOW()
                      WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur"; // S'assurer que l'utilisateur est bien le propriétaire
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':prix', $prix, PDO::PARAM_STR);
            $stmt->bindParam(':id_categorie', $id_categorie, PDO::PARAM_INT);
            $stmt->bindParam(':id_annonce', $id_annonce, PDO::PARAM_INT);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la mise à jour de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }

    // Supprime une annonce [cite: 31]
    public function deleteAnnonce($id_annonce, $id_utilisateur) {
        try {
            $query = "DELETE FROM annonces WHERE id_annonce = :id_annonce AND id_utilisateur = :id_utilisateur"; // S'assurer que l'utilisateur est bien le propriétaire
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_annonce', $id_annonce, PDO::PARAM_INT);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log('Erreur lors de la suppression de l\'annonce : ' . $e->getMessage());
            return false;
        }
    }
}