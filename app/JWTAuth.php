<?php
  namespace AttendanceApp;
  use Firebase\JWT\JWT;

  require_once 'config/Config.php';

  class JWTAuth {
    public static function is_valid_token($token)
    {
      return true;
    }
  }
