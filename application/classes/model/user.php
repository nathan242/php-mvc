<?php
    namespace application\model;

    use framework\db\interfaces\db_interface;
    use framework\db\sql_builder;
    use framework\mvc\session;
    use framework\model\model;
    use framework\model\model_collection;

    class user extends model {
        protected $table = 'users';
        protected $session;

        public function __construct(db_interface $db, sql_builder $sql_builder, model_collection $model_collection, session $session) {
            $this->session = $session;
            parent::__construct($db, $sql_builder, $model_collection);
        }

        /**
         * Check if a valid user is logged in.
         *
         * @return boolean
         */
        public function check_logged_in() {
            if (
                !isset($this->session->user_id)
                || !$this->retrieve_where(['id' => $this->session->user_id, 'enabled' => 1])
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
                $this->session->user_id = $this->id;
                $this->session->loginuser = $username;
                return true;
            }
        }
    }
