<?php

namespace AttendanceApp;

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/.env');

class Config
{
  public static function getJWTKey()
  {
    return $_ENV["JWT_KEY"];
  }
}
