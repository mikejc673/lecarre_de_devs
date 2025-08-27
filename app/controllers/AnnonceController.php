<?php
// app/controllers/AnnonceController.php

require_once APP_PATH . '/controllers/BaseController.php';
require_once APP_PATH . '/config/models/AnnonceModel.php';
require_once APP_PATH . '/config/models/CategoryModel.php';

class AnnonceController extends BaseController {
    private $annonceModel;
    private $categoryModel;

    public function __construct() {
        parent::__construct(); // Appeler le constructeur parent pour la session
        $this->annonceModel = new AnnonceModel();
        $this->categoryModel = new CategoryModel();
    }

    // Affiche le détail d'une annonce
    public function detail($id_annonce) {
        try {
            $annonce = $this->annonceModel->getAnnonceById($id_annonce);
            if (!$annonce) {
                // Gérer le cas où l'annonce n'existe pas
                $_SESSION['error_message'] = "L'annonce demandée n'existe pas ou a été supprimée.";
                $this->redirect('/');
                return;
            }
            $this->loadView('annonces/detail', ['annonce' => $annonce]);
        } catch (Exception $e) {
            error_log('Erreur lors de l\'affichage de l\'annonce : ' . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue lors du chargement de l'annonce.";
            $this->redirect('/');
        }
    }

    // Affiche le formulaire d'ajout/modification d'annonce
    public function addEdit($id_annonce = null) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Vous devez être connecté pour ajouter ou modifier une annonce.";
            $this->redirect('/login');
            return;
        }

        $annonce = null;
        if ($id_annonce) {
            $annonce = $this->annonceModel->getAnnonceById($id_annonce);
            // S'assurer que l'utilisateur est le propriétaire de l'annonce
            if (!$annonce || $annonce['id_utilisateur'] != $_SESSION['user_id']) {
                $_SESSION['error_message'] = "Vous n'avez pas l'autorisation de modifier cette annonce.";
                $this->redirect('/my-annonces');
                return;
            }
        }

        $categories = $this->categoryModel->getAllCategories();
        $data = ['annonce' => $annonce, 'categories' => $categories];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $titre = trim($_POST['titre'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $prix = floatval(str_replace(',', '.', $_POST['prix'] ?? '')); // Gérer la virgule comme séparateur décimal
            $id_categorie = intval($_POST['categorie'] ?? 0);
            $id_utilisateur = $_SESSION['user_id'];

            if (empty($titre) || empty($description) || $prix <= 0 || $id_categorie <= 0) {
                $data['error'] = "Tous les champs sont requis et valides.";
            } else {
                if ($id_annonce) {
                    // Modification
                    if ($this->annonceModel->updateAnnonce($id_annonce, $titre, $description, $prix, $id_categorie, $id_utilisateur)) {
                        $_SESSION['success_message'] = "Annonce modifiée avec succès ! [cite: 30, 33]";
                        $this->redirect('/my-annonces');
                    } else {
                        $data['error'] = "Erreur lors de la modification de l'annonce.";
                    }
                } else {
                    // Ajout
                    if ($this->annonceModel->addAnnonce($titre, $description, $prix, $id_categorie, $id_utilisateur)) {
                        $_SESSION['success_message'] = "Annonce ajoutée avec succès ! [cite: 29, 33]";
                        $this->redirect('/my-annonces');
                    } else {
                        $data['error'] = "Erreur lors de l'ajout de l'annonce.";
                    }
                }
            }
        }
        $this->loadView('annonces/add_edit', $data);
    }

    // Supprime une annonce
    public function delete($id_annonce) {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Vous devez être connecté pour supprimer une annonce.";
            $this->redirect('/login');
            return;
        }

        $id_utilisateur = $_SESSION['user_id'];
        $annonce = $this->annonceModel->getAnnonceById($id_annonce);

        // Vérifier si l'annonce existe et appartient à l'utilisateur connecté
        if (!$annonce || $annonce['id_utilisateur'] != $id_utilisateur) {
            $_SESSION['error_message'] = "Vous n'avez pas l'autorisation de supprimer cette annonce.";
            $this->redirect('/my-annonces');
            return;
        }

        if ($this->annonceModel->deleteAnnonce($id_annonce, $id_utilisateur)) {
            $_SESSION['success_message'] = "Annonce supprimée avec succès ! [cite: 31, 33]";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la suppression de l'annonce.";
        }
        $this->redirect('/my-annonces');
    }

    // Affiche la liste des annonces de l'utilisateur connecté
    public function myAnnonces() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Vous devez être connecté pour voir vos annonces.";
            $this->redirect('/login');
            return;
        }

        try {
            $id_utilisateur = $_SESSION['user_id'];
            $annonces = $this->annonceModel->getUserAnnonces($id_utilisateur);

            $data = ['annonces' => $annonces];

            // Gérer les messages de succès/erreur de session
            if (isset($_SESSION['success_message'])) {
                $data['success'] = $_SESSION['success_message'];
                unset($_SESSION['success_message']);
            }
            if (isset($_SESSION['error_message'])) {
                $data['error'] = $_SESSION['error_message'];
                unset($_SESSION['error_message']);
            }

            $this->loadView('annonces/my_annonces', $data);
        } catch (Exception $e) {
            error_log('Erreur lors du chargement des annonces utilisateur : ' . $e->getMessage());
            $_SESSION['error_message'] = "Une erreur est survenue lors du chargement de vos annonces.";
            $this->redirect('/');
        }
    }
}