<?php

namespace controllers;

use dal\ModuleDAOImpl;

class ModuleController
{
    private $moduleDAO;
    public function __construct()
    {
        $this->moduleDAO = new ModuleDAOImpl();
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
}
