<?php
    namespace commands;

    class initialization_commands extends base_command {
        protected $db;

        public function __construct($db) {
            $this->db = $db;
        }

        public function create_users_table() {
            echo "Creating users table ... ";
            if (!$this->db->query("CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `enabled` TINYINT(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1")) {
                echo "Failed\n\nFailed to create user table.\nError: ".$this->db->last_error()."\n";
                return 1;
            }

            echo "Done\nInserting admin user ... ";
            if (!$this->db->query("INSERT INTO `users` (`username`, `password`, `enabled`) VALUES ('admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1)")) {
                echo "Failed\n\nFailed to insert admin user.\nError: ".$this->db->last_error()."\n";
                return 1;
            }

            echo "Done\n";

            return 0;
        }
    }
