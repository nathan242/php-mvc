<?php
    namespace command;

    use db\interfaces\db_interface;
    use db\sql_builder;

    class initialization_commands extends base_command {
        protected $db;
        protected $sql_builder;

        public function __construct(db_interface $db, sql_builder $sql_builder) {
            $this->db = $db;
            $this->sql_builder = $sql_builder;
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
