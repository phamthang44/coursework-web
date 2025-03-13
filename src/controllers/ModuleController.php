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

    public function getModule($moduleId)
    {
        return $this->moduleDAO->getModule($moduleId)->getModuleName();
    }
}
