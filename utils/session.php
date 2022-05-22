<?php
  class Session {
    private array $messages;

    public function __construct() {
      session_start();

      $this->messages = isset($_SESSION['messages']) ? $_SESSION['messages'] : array();
      unset($_SESSION['messages']);
    }

    public function isLoggedIn() : bool {
      return isset($_SESSION['email']);    
    }

    public function logout() {
      session_unset();
      session_destroy();
    }

    public function getEmail() : ?string {
      return isset($_SESSION['email']) ? $_SESSION['email'] : null;    
    }

    public function getName() : ?string {
      return isset($_SESSION['name']) ? $_SESSION['name'] : null;
    }

    public function setEmail(string $email) {
      $_SESSION['email'] = $email;
    }

    public function setName(string $name) {
      $_SESSION['name'] = $name;
    }

    public function getLoginStatus() : ? bool{
      return isset($_SESSION['login_status']) ? $_SESSION['login_status'] : null;
    }

    public function setLoginStatus() {
      $_SESSION['login_status'] = true;
    }

    public function setLogoutStatus() {
      $_SESSION['login_status'] = false;
    }

    public function addMessage(string $type, string $text) {
      $_SESSION['messages'][] = array('type' => $type, 'text' => $text);
    }

    public function getMessages() {
      return $this->messages;
    }
  }
?>