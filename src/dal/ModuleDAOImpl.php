<?php

namespace dal;

use database\Database;
use models\Module;
use dal\ModuleDAOI;
use PDO;

class ModuleDAOImpl implements ModuleDAOI
{
    private $pdo;
    public function __construct()
    {
        $db = new Database();
        $this->pdo = $db->getConnection();
    }
    public function insertModule($moduleName, $moduleDescription)
    {
        // TODO: Implement insertModule() method.
        $conn = $this->pdo;
        $sql = "INSERT INTO modules (module_name, description) VALUES (:moduleName, :moduleDescription)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':moduleName', $moduleName);
        $stmt->bindParam(':moduleDescription', $moduleDescription);
        return [
            "success" => $stmt->execute(),
            "moduleId" => intval($conn->lastInsertId())
        ];
    }

    public function updateModule($moduleName, $moduleDescription, $moduleId)
    {
        // TODO: Implement updateModule() method.
        $conn = $this->pdo;
        $sql = "UPDATE modules SET description = :moduleDescription, module_name = :moduleName WHERE module_id = :moduleId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':moduleDescription', $moduleDescription);
        $stmt->bindParam(':moduleName', $moduleName);
        $stmt->bindParam(':moduleId', $moduleId);
        return $stmt->execute();
    }

    public function checkExistingModule($moduleName)
    {
        // TODO: Implement checkExistingModule() method.
        $conn = $this->pdo;
        $sql = "SELECT * FROM Modules WHERE module_name = :moduleName";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':moduleName', $moduleName);
        return $stmt->execute();
    }

    public function checkExistingModuleId($moduleId)
    {
        // TODO: Implement checkExistingModuleId() method.
        $conn = $this->pdo;
        $sql = "SELECT * FROM Modules WHERE module_id = :moduleId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':moduleId', $moduleId);
        return $stmt->execute();
    }

    public function deleteModule($moduleId)
    {
        // TODO: Implement deleteModule() method.
        $conn = $this->pdo;
        $sql = "DELETE FROM Modules WHERE module_id = :moduleId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':moduleId', $moduleId);
        return $stmt->execute();
    }

    public function getModule($moduleId): Module
    {
        // TODO: Implement getModule() method.
        $conn = $this->pdo;
        $sql = "SELECT * FROM Modules WHERE module_id = :moduleId";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':moduleId', $moduleId);
        $stmt->execute();
        $row = $stmt->fetch();
        $module = new Module($row['module_id'], $row['module_name'], $row['description']);
        return $module;
    }

    public function getModulesByName($moduleName)
    {
        $searchTerm = "%{$moduleName}%";
        $sql = "SELECT * FROM Modules WHERE module_name LIKE :moduleName LIMIT 5";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":moduleName", $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // if only 1, return it
        if (count($rows) === 1) {
            $row = $rows[0];
            $module = new Module($row['module_id'], $row['module_name'], $row['description']);
            return $module;
        }

        // if not  or more than 1 result return array
        $modules = [];
        foreach ($rows as $row) {
            $module = new Module($row['module_id'], $row['module_name'], $row['description']);
            $modules[] = $module;
        }
        return $modules;
    }

    public function getAllModules()
    {
        // TODO: Implement getAllModules() method.
        $conn = $this->pdo;
        $sql = "SELECT * FROM Modules";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $modules = [];
        foreach ($rows as $row) {
            $module = new Module($row['module_id'], $row['module_name'], $row['description']);
            $modules[] = $module;
        }
        return $modules;
    }

    public function getTotalModuleNums()
    {
        $conn = $this->pdo;
        $sql = "SELECT COUNT(*) FROM Modules";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getModulesPerPage($offset, $limit)
    {
        $sql = "SELECT * FROM Modules LIMIT :limit OFFSET :offset";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $modules = [];
        foreach ($rows as $row) {
            $module = new Module($row['module_id'], $row['module_name'], $row['description']);
            $modules[] = $module;
        }
        return $modules;
    }
}
