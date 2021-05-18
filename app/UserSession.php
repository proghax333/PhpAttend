<?php
  namespace AttendanceApp;

  class UserSession {
    public function __construct($timestamp)
    {
      $this->creation_timestamp = $timestamp;
      $this->last_timestamp = $timestamp;
      $this->count = 0;
    }
  }
