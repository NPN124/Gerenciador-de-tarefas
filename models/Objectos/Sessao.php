<?php
    class Sessao {

        private $id;
        private $usuario_id;
        private $user_agent;
        private $isValid;
        private $token;
        private $data_login;
        private $ultimo_acesso;


        public function __construct($id, $usuario_id, $user_agent,
                 $token, $isValid = true, $data_login = null, $ultimo_acesso = null){
            $this->id = $id;
            $this->usuario_id = $usuario_id;
            $this->user_agent = $user_agent;
            $this->isValid = $isValid;
            $this->token = $token;
            $this->data_login = $data_login ? $data_login : date('Y-m-d H:i:s');
            $this->ultimo_acesso = $ultimo_acesso ? $ultimo_acesso : date('Y-m-d H:i:s');

        }

        public function getId() { return $this->id; }
        public function getUsuarioId() { return $this->usuario_id; }
        public function getUserAgent() { return $this->user_agent; }
        public function getIsValid() { return $this->isValid; }
        public function getToken() { return $this->token; }
        public function getDataLogin() { return $this->data_login; }
        public function getUltimoAcesso() { return $this->ultimo_acesso; }


        public function setId($id) { $this->id = $id; }
        public function setUsuarioId($usuario_id) { $this->usuario_id = $usuario_id; }
        public function setUserAgent($user_agent) { $this->user_agent = $user_agent; }
        public function setIsValid($isValid) { $this->isValid = $isValid; }
        public function setToken($token) { $this->token = $token; }
        public function setDataLogin($data_login) { $this->data_login = $data_login; }
        public function setUltimoAcesso($ultimo_acesso) { $this->ultimo_acesso = $ultimo_acesso; }

    }
?>