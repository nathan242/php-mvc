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
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
)")) {
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
