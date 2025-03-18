<?php

namespace dal;

interface ModuleDAOI
{
    public function insertModule($moduleName, $moduleDescription);
    public function updateModule($moduleName, $moduleDescription, $moduleId);
    public function checkExistingModule($moduleName);
    public function checkExistingModuleId($moduleId);
    public function deleteModule($moduleId);
    public function getModule($moduleId);
    public function getModulesByName($moduleName);
    public function getAllModules();
    public function getTotalModuleNums();
    public function getModulesPerPage($offset, $limit);
}
