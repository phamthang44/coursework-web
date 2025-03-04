<?php
    class Module {
        private $moduleId;
        private $moduleName;
        private $moduleDescription;

        /**
         * @param $moduleId
         * @param $moduleName
         * @param $moduleDescription
         */
        public function __construct($moduleId, $moduleName, $moduleDescription)
        {
            $this->moduleId = $moduleId;
            $this->moduleName = $moduleName;
            $this->moduleDescription = $moduleDescription;
        }

        /**
         * @return mixed
         */
        public function getModuleId()
        {
            return $this->moduleId;
        }

        /**
         * @param mixed $moduleId
         */
        public function setModuleId($moduleId)
        {
            $this->moduleId = $moduleId;
        }

        /**
         * @return mixed
         */
        public function getModuleName()
        {
            return $this->moduleName;
        }

        /**
         * @param mixed $moduleName
         */
        public function setModuleName($moduleName)
        {
            $this->moduleName = $moduleName;
        }

        /**
         * @return mixed
         */
        public function getModuleDescription()
        {
            return $this->moduleDescription;
        }

        /**
         * @param mixed $moduleDescription
         */
        public function setModuleDescription($moduleDescription)
        {
            $this->moduleDescription = $moduleDescription;
        }

    }
?>