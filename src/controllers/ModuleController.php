<?php

namespace controllers;

use dal\ModuleDAOImpl;


class ModuleController
{
    private $moduleDAO;
    public function __construct()
    {
        $this->moduleDAO = new ModuleDAOImpl();

        // if (!$this->currentUser) {
        //     header("Location: /login");
        //     exit();
        // }
        // 
        // if (!$this->currentUser->getRole() !== 'admin') {
        //     header("Location: /403"); // Trang báo lỗi quyền hạn
        //     exit();
        // }
    }

    public function moduleManagement()
    {
        require_once __DIR__ . '/../views/admin/module_management.php';
    }

    public function getModuleName($moduleId)
    {
        return $this->moduleDAO->getModule($moduleId)->getModuleName();
    }

    public function getModuleById($moduleId)
    {
        return $this->moduleDAO->getModule($moduleId);
    }
    public function getAllModules()
    {
        return $this->moduleDAO->getAllModules();
    }
    public function getTotalModuleNums()
    {
        return $this->moduleDAO->getTotalModuleNums();
    }
}
