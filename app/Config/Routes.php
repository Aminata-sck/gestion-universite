<?php

namespace Config;

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

// Routes publiques
$routes->get('/', 'DashboardController::index');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->get('/forgot-password', 'AuthController::forgotPassword');
$routes->post('/forgot-password', 'AuthController::sendResetLink');
$routes->get('/reset-password/(:any)', 'AuthController::resetPassword/$1');
$routes->post('/reset-password', 'AuthController::updatePassword');
$routes->get('ues/bulk-add', 'UeController::bulkAdd');
$routes->post('ues/bulk-store', 'UeController::bulkStore');
$routes->get('admin/dashboard', 'AdminDashboardController::index');
// Route de test
$routes->get('/test', 'TestController::index');

// Routes protégées par authentification
$routes->group('', ['filter' => 'auth'], function($routes) {
    $routes->get('/dashboard', 'DashboardController::index');
    
    $routes->get('profils', 'ProfilController::index');
$routes->get('profils/new', 'ProfilController::new');
$routes->post('profils', 'ProfilController::create');
$routes->get('profils/edit/(:num)', 'ProfilController::edit/$1');
$routes->post('profils/update/(:num)', 'ProfilController::update/$1');
$routes->get('profils/delete/(:num)', 'ProfilController::delete/$1');
$routes->get('absences/get-etudiants/(:num)', 'AbsenceController::getEtudiantsByEmploi/$1');
    // Gestion des utilisateurs
    $routes->get('users', 'UserController::index');
    $routes->get('users/new', 'UserController::new');
    $routes->post('users', 'UserController::create');
    $routes->get('users/edit/(:num)', 'UserController::edit/$1');
    $routes->post('users/update/(:num)', 'UserController::update/$1');
    $routes->get('users/delete/(:num)', 'UserController::delete/$1');

    // Formations
   // Formations
$routes->get('formations', 'FormationController::index');
$routes->get('formations/new', 'FormationController::new');
$routes->post('formations', 'FormationController::create');
$routes->get('formations/edit/(:num)', 'FormationController::edit/$1');
$routes->post('formations/update/(:num)', 'FormationController::update/$1');
$routes->get('formations/archive/(:num)', 'FormationController::archive/$1');
$routes->get('formations/restore/(:num)', 'FormationController::restore/$1');
$routes->get('formations/archived', 'FormationController::archived');
    // UE
    $routes->get('ues', 'UeController::index');
    $routes->get('ues/new', 'UeController::new');
    $routes->post('ues', 'UeController::create');
    $routes->get('ues/(:num)', 'UeController::show/$1');
    $routes->get('ues/edit/(:num)', 'UeController::edit/$1');
    $routes->post('ues/update/(:num)', 'UeController::update/$1');
    $routes->get('ues/delete/(:num)', 'UeController::delete/$1');

    // EC
    $routes->get('ecs', 'EcController::index');
    $routes->get('ecs/ue/(:num)/new', 'EcController::newForUe/$1');
    $routes->post('ecs/ue/(:num)', 'EcController::createForUe/$1');
    $routes->get('ecs/new', 'EcController::new');
    $routes->post('ecs', 'EcController::create');
    $routes->get('ecs/edit/(:num)', 'EcController::edit/$1');
    $routes->post('ecs/update/(:num)', 'EcController::update/$1');
    $routes->get('ecs/delete/(:num)', 'EcController::delete/$1');
$routes->get('ecs/bulk-add', 'EcController::bulkAdd');
$routes->post('ecs/bulk-store', 'EcController::bulkStore');
    // Inscriptions administratives
    $routes->get('inscriptions-admin', 'InscriptionAdminController::index');
    $routes->get('inscriptions-admin/new', 'InscriptionAdminController::new');
    $routes->post('inscriptions-admin', 'InscriptionAdminController::create');
    $routes->get('inscriptions-admin/edit/(:num)', 'InscriptionAdminController::edit/$1');
    $routes->post('inscriptions-admin/update/(:num)', 'InscriptionAdminController::update/$1');
    $routes->get('inscriptions-admin/delete/(:num)', 'InscriptionAdminController::delete/$1');
// Inscriptions administratives
$routes->get('inscriptions-admin/bulk-add', 'InscriptionAdminController::bulkAdd');
$routes->post('inscriptions-admin/bulk-store', 'InscriptionAdminController::bulkStore');
    // Inscriptions pédagogiques
    $routes->get('inscriptions-pedagogiques', 'InscriptionPedagogiqueController::index');
    $routes->get('inscriptions-pedagogiques/new', 'InscriptionPedagogiqueController::new');
    $routes->post('inscriptions-pedagogiques', 'InscriptionPedagogiqueController::create');
    $routes->get('inscriptions-pedagogiques/edit/(:num)', 'InscriptionPedagogiqueController::edit/$1');
    $routes->post('inscriptions-pedagogiques/update/(:num)', 'InscriptionPedagogiqueController::update/$1');
    $routes->get('inscriptions-pedagogiques/delete/(:num)', 'InscriptionPedagogiqueController::delete/$1');
$routes->get('inscriptions-pedagogiques/bulk-add', 'InscriptionPedagogiqueController::bulkAdd');
$routes->post('inscriptions-pedagogiques/bulk-store', 'InscriptionPedagogiqueController::bulkStore');
    // Notes
    $routes->get('notes', 'NoteController::index');
    $routes->get('notes/new', 'NoteController::new');
    $routes->post('notes', 'NoteController::create');
    $routes->get('notes/edit/(:num)', 'NoteController::edit/$1');
    $routes->post('notes/update/(:num)', 'NoteController::update/$1');
    $routes->get('notes/delete/(:num)', 'NoteController::delete/$1');
$routes->get('notes/get-ecs/(:num)', 'NoteController::getEcsByStudent/$1');
// Routes pour l'ajout groupé
$routes->get('notes/bulk-select', 'NoteController::bulkSelect');
$routes->get('notes/bulk-add', 'NoteController::bulkAdd');
$routes->post('notes/bulk-store', 'NoteController::bulkStore');
// Route AJAX
$routes->get('notes/get-ecs/(:num)', 'NoteController::getEcsByStudent/$1');
    // Emplois du temps
    $routes->get('emplois', 'EmploiController::index');
    $routes->get('emplois/new', 'EmploiController::new');
    $routes->post('emplois', 'EmploiController::create');
    $routes->get('emplois/edit/(:num)', 'EmploiController::edit/$1');
    $routes->post('emplois/update/(:num)', 'EmploiController::update/$1');
    $routes->get('emplois/delete/(:num)', 'EmploiController::delete/$1');
$routes->get('emplois/bulk-add', 'EmploiController::bulkAdd');
$routes->post('emplois/bulk-store', 'EmploiController::bulkStore');
    // Absences
  // Absences
$routes->get('absences', 'AbsenceController::index');
$routes->get('absences/new', 'AbsenceController::new');
$routes->post('absences', 'AbsenceController::create');
$routes->get('absences/edit/(:num)', 'AbsenceController::edit/$1');
$routes->post('absences/update/(:num)', 'AbsenceController::update/$1');
$routes->get('absences/delete/(:num)', 'AbsenceController::delete/$1');

// Routes groupées
$routes->get('absences/bulk-add', 'AbsenceController::bulkAdd');
$routes->post('absences/bulk-store', 'AbsenceController::bulkStore');

// AJAX
$routes->get('absences/get-etudiants/(:num)', 'AbsenceController::getEtudiantsByEc/$1');
    // Paiements
   
$routes->get('paiements', 'PaiementController::index');
$routes->get('paiements/new', 'PaiementController::new');
$routes->post('paiements', 'PaiementController::create');
$routes->get('paiements/edit/(:num)', 'PaiementController::edit/$1');
$routes->post('paiements/update/(:num)', 'PaiementController::update/$1');
$routes->get('paiements/delete/(:num)', 'PaiementController::delete/$1');
$routes->get('paiements/bulk-add', 'PaiementController::bulkAdd');
$routes->get('paiements/get-paiements/(:num)/(:num)', 'PaiementController::getPaiementsByEtudiant/$1/$2');
$routes->post('paiements/ajouter-progressif', 'PaiementController::ajouterPaiementProgressif');
// Optionnel : gardé pour compatibilité si nécessaire
$routes->get('paiements/get-etudiants/(:num)/(:num)', 'PaiementController::getEtudiantsByFormation/$1/$2');
$routes->get('paiements/get-etudiants/(:num)/(:num)', 'PaiementController::getEtudiantsByFormation/$1/$2');
    // Espace étudiant
$routes->group('student', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'StudentController::dashboard');
    $routes->get('grades', 'StudentController::grades');
    $routes->get('schedule', 'StudentController::schedule');
    $routes->get('absences', 'StudentController::absences');
    $routes->get('payments', 'StudentController::payments');
});
// Tableau de bord admin
$routes->get('admin/dashboard', 'AdminDashboardController::index');

// Espace enseignant
$routes->group('teacher', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'TeacherController::dashboard');
    $routes->get('courses', 'TeacherController::courses');
    $routes->get('schedule', 'TeacherController::schedule');

    // Gestion des emplois du temps
    $routes->get('schedule/create/(:num)', 'TeacherController::scheduleCreate/$1');
    $routes->post('schedule/store', 'TeacherController::scheduleStore');
    $routes->get('schedule/edit/(:num)', 'TeacherController::scheduleEdit/$1');
    $routes->post('schedule/update/(:num)', 'TeacherController::scheduleUpdate/$1');
    $routes->get('schedule/delete/(:num)', 'TeacherController::scheduleDelete/$1');

    // Gestion des notes
    $routes->get('grades/(:num)', 'TeacherController::grades/$1');
    $routes->post('save-grades', 'TeacherController::saveGrades');
    $routes->get('grades/edit/(:num)', 'TeacherController::editGrade/$1');
    $routes->post('grades/update/(:num)', 'TeacherController::updateGrade/$1');
    $routes->get('grades/delete/(:num)', 'TeacherController::deleteGrade/$1');

    // Gestion des absences
    $routes->get('absences/(:num)', 'TeacherController::absences/$1');
    $routes->post('save-absences', 'TeacherController::saveAbsences');
    $routes->get('absences/edit/(:num)', 'TeacherController::editAbsence/$1');
    $routes->post('absences/update/(:num)', 'TeacherController::updateAbsence/$1');
    $routes->get('absences/delete/(:num)', 'TeacherController::deleteAbsence/$1');
});
});
