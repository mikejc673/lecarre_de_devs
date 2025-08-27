<?php
// app/controllers/AuthController.php

require_once APP_PATH . '/controllers/BaseController.php';
require_once APP_PATH . '/config/models/UserModel.php';

class AuthController extends BaseController {
    private $userModel;

    public function __construct() {
        parent::__construct(); // Appeler le constructeur parent pour la session
        $this->userModel = new UserModel();
    }

    public function register() {
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            // Validation simple côté serveur
            if (empty($email) || empty($password) || empty($confirm_password)) {
                $data['error'] = "Tous les champs sont requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $data['error'] = "Format d'email invalide.";
            } elseif ($password !== $confirm_password) {
                $data['error'] = "Les mots de passe ne correspondent pas.";
            } elseif (strlen($password) < 6) {
                $data['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
            } else {
                try {
                    if ($this->userModel->emailExists($email)) {
                        $data['error'] = "Cet email est déjà enregistré.";
                    } else {
                        $password_hashed = password_hash($password, PASSWORD_DEFAULT); // Hashage du mot de passe 
                        if ($this->userModel->registerUser($email, $password_hashed)) {
                            $_SESSION['success_message'] = "Votre compte a été créé avec succès ! Veuillez vous connecter.";
                            $this->redirect('/login');
                        } else {
                            $data['error'] = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                        }
                    }
                } catch (Exception $e) {
                    error_log('Erreur lors de l\'inscription : ' . $e->getMessage());
                    $data['error'] = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                }
            }
        }
        $this->loadView('auth/register', $data);
    }

    public function login() {
        $data = [];
        // Vérifier si un message de succès est présent de l'inscription
        if (isset($_SESSION['success_message'])) {
            $data['success'] = $_SESSION['success_message'];
            unset($_SESSION['success_message']); // Supprimer le message après l'affichage
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($email) || empty($password)) {
                $data['error'] = "Veuillez entrer votre email et votre mot de passe.";
            } else {
                try {
                    $user = $this->userModel->getUserByEmail($email);

                    if ($user && password_verify($password, $user['mot_de_passe'])) {
                        // Connexion réussie, démarrer la session 
                        $_SESSION['user_id'] = $user['id_utilisateur'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['welcome_message'] = "Bonjour, " . htmlspecialchars($user['email']) . " !"; // Message de bienvenue 
                        $this->redirect('/'); // Rediriger vers l'accueil ou le tableau de bord
                    } else {
                        $data['error'] = "Email ou mot de passe incorrect.";
                    }
                } catch (Exception $e) {
                    error_log('Erreur lors de la connexion : ' . $e->getMessage());
                    $data['error'] = "Une erreur est survenue lors de la connexion. Veuillez réessayer.";
                }
            }
        }
        $this->loadView('auth/login', $data);
    }

    public function logout() {
        session_start(); // S'assurer que la session est démarrée pour la détruire 
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        $this->redirect('/'); // Rediriger vers la page d'accueil
    }
}