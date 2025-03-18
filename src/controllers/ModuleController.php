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
    public function getCurrentPage()
    {
        return isset($_GET['page']) ? $_GET['page'] : 1;
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
    public function getModulesPerPage($offset, $limit)
    {
        return $this->moduleDAO->getModulesPerPage($offset, $limit);
    }

    public function updateModule($moduleName, $moduleDescription, $moduleId)
    {
        return $this->moduleDAO->updateModule($moduleName, $moduleDescription, $moduleId);
    }

    public function deleteModule($moduleId)
    {
        return $this->moduleDAO->deleteModule($moduleId);
    }

    public function createModule($moduleName, $moduleDescription)
    {
        return $this->moduleDAO->insertModule($moduleName, $moduleDescription);
    }
}
