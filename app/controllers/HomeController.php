<?php
// app/controllers/HomeController.php

require_once APP_PATH . '/controllers/BaseController.php';
require_once APP_PATH . '/config/models/AnnonceModel.php';
require_once APP_PATH . '/config/models/CategoryModel.php';

class HomeController extends BaseController {
    private $annonceModel;
    private $categoryModel;

    public function __construct() {
        parent::__construct(); // Appeler le constructeur parent pour la session
        $this->annonceModel = new AnnonceModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index() {
        $annonces = $this->annonceModel->getAllAnnonces();
        $categories = $this->categoryModel->getAllCategories();

        $this->loadView('home/index', [
            'annonces' => $annonces,
            'categories' => $categories
        ]);
    }
}