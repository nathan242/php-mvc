<?php
    namespace model;

    use db\interfaces\db_interface;
    use db\sql_builder;

    class user extends model {
        protected $table = 'users';
        private $session;

        public function __construct(db_interface $db, sql_builder $sql_builder, $session) {
            $this->session = $session;
            parent::__construct($db, $sql_builder);
        }

        /**
         * Check if a valid user is logged in.
         *
         * @return boolean
         */
        public function check_logged_in() {
            if (
                !$this->session->has('user_id')
                || !$this->retrieve_where(['id' => $this->session->get('user_id'), 'enabled' => 1])
            ) {
                return false;
            }

            return true;
        }

        /**
         * Attempt to log in. If successful the user data will be set in the session.
         * 
         * @param string $username Username
         * @param string $password Password
         * @return boolean
         */
        public function login($username, $password) {
            $hash = hash('sha256', $password);
            if (!$this->retrieve_where(['username' => $username, 'password' => $hash, 'enabled' => 1])) {
                return false;
            } else {
                $this->session->set('user_id', $this->id);
                $this->session->set('loginuser', $username);
                return true;
            }
        }
    }
