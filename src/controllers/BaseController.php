<?php

namespace controllers;

use utils\SessionManager;

class BaseController
{
    protected $currentUser;

    public function __construct($allowedRoutes = [], $forbiddenRoutes = [])
    {
        $this->currentUser = SessionManager::get('user');
        $currentRoute = $_SERVER['REQUEST_URI'];
        $isAdmin = $this->currentUser && $this->currentUser->getRole() === 'admin';
        // If page in forbidden page -> restrict access
        if (in_array($currentRoute, $forbiddenRoutes) && !$isAdmin) {
            SessionManager::set('error', 'You do not have permission to access this page');
            header("Location: /403"); // Redirect to page "No access"
            exit();
        }
        // If user is not logged in
        if (!$this->currentUser) {
            if ($this->isFetchRequest()) {
                echo json_encode(['message' => 'You must be logged in to use this feature!']);
                exit();
            }
            // If page is not public -> require login
            if (in_array($currentRoute, $forbiddenRoutes)) {
                header("Location: /403"); // Redirect to page "No access"
                exit();
            }
            if (!in_array($currentRoute, $allowedRoutes)) {
                header("Location: /login");
                exit();
            }
        }
    }
    private function isFetchRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'FetchRequest';
    }
}
