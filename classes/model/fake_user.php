<?php
    namespace model;

    class fake_user {
        private $session;

        public function __construct($db, $session) {
            $this->session = $session;
        }

        /**
         * Check if a valid user is logged in.
         *
         * @return boolean
         */
        public function check_logged_in() {
            if (!$this->session->has('user_id')) {
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
            $this->session->set('user_id', 0);
            $this->session->set('loginuser', 'fake_user');
            return true;
        }
    }
