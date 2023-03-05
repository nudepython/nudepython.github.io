<?php

// PHP Email Form Class
class PHP_Email_Form {

  public $to;
  public $from_name;
  public $from_email;
  public $subject;
  public $message;
  public $headers;
  public $smtp;

  function __construct() {
    $this->to = '';
    $this->from_name = '';
    $this->from_email = '';
    $this->subject = '';
    $this->message = '';
    $this->headers = array();
    $this->smtp = array();
  }

  function add_message($message, $name = '') {
    if ($name == '') {
      $this->message = $message;
    } else {
      $this->message .= "$name: $message\n";
    }
  }

  function add_header($name, $value) {
    $this->headers["$name"] = "$value";
  }

  function send() {
    if (count($this->smtp) == 0) {
      $this->headers[] = "MIME-Version: 1.0";
      $this->headers[] = "Content-type: text/plain; charset=utf-8";
      $this->headers[] = "From: {$this->from_name} <{$this->from_email}>";
      $this->headers[] = "Reply-To: {$this->from_name} <{$this->from_email}>";
      $this->headers[] = "Subject: {$this->subject}";
    } else {
      $smtp_host = $this->smtp['host'];
      $smtp_username = $this->smtp['username'];
      $smtp_password = $this->smtp['password'];
      $smtp_port = $this->smtp['port'];
      $smtp_tls = isset($this->smtp['tls']) ? $this->smtp['tls'] : false;
      $smtp_auth = isset($this->smtp['auth']) ? $this->smtp['auth'] : true;

      $this->headers[] = "MIME-Version: 1.0";
      $this->headers[] = "Content-type: text/plain; charset=utf-8";
      $this->headers[] = "From: {$this->from_name} <{$this->from_email}>";
      $this->headers[] = "Reply-To: {$this->from_name} <{$this->from_email}>";
      $this->headers[] = "Subject: {$this->subject}";

      $smtp_connect = fsockopen($smtp_host, $smtp_port, $errno, $errstr, 15);
      if (!$smtp_connect) {
        return "Error: {$errno} - {$errstr}";
      }

      $smtp_response = fgets($smtp_connect, 515);
      if (substr($smtp_response, 0, 3) != '220') {
        return "Error: Unexpected response from SMTP server: {$smtp_response}";
      }

      if ($smtp_auth) {
        fputs($smtp_connect, "EHLO {$smtp_host}\r\n");
        $response = fgets($smtp_connect, 4096);
        if(substr($response, 0, 3) != 220 && substr($response, 0, 3) != 250) {
            $errors[] = 'Connection error: ' . $response;
            return false;
        }
    }

        // If the SMTP server requires authentication
        if($smtp_auth) {
        // Send the username
        fputs($smtp_connect, "AUTH LOGIN\r\n");
        $response = fgets($smtp_connect, 4096);
        if(substr($response, 0, 3) != 334) {
            $errors[] = 'Auth error: ' . $response;
            return false;
        }
    }

        // Send the encoded username
        fputs($smtp_connect, base64_encode($smtp_username) . "\r\n");
        $response = fgets($smtp_connect, 4096);
        if(substr($response, 0, 3) != 334) {
            $errors[] = 'Auth error: ' . $response;
            return false;
        }
    }

        // Send the encoded password
        fputs($smtp_connect, base64_encode($smtp_password) . "\r\n");
        $response = fgets($smtp_connect, 4096);
        if(substr($response, 0, 3) != 235) {
            $errors[] = 'Auth error: ' . $response;
            return false;
        }
    }
    }