<?php

  $routes->get('/', function() {
    if (empty($_SESSION['user'])) {
     UserController::login();
    } else {
      if (is_null($_SESSION['user'])) {
        UserController::login();
      } else {
        TaskController::tasklist();
      }  
    }

  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/task', function() {
    TaskController::tasklist();
  });

  $routes->post('/task/insert', function() {
    TaskController::store('insert');
  });

  $routes->post('/task/update', function() {
    TaskController::store('update');
  });

  $routes->get('/login', function(){
  // Kirjautumislomakkeen esittäminen
    UserController::login();
  });
  $routes->post('/login', function(){
  // Kirjautumisen käsittely
    UserController::handle_login();
  });
  $routes->get('/logout', function(){
  // Kirjautumisen käsittely
    $_SESSION['user'] = null;
    UserController::logout();
  });


  $routes->get('/task/delete/:key', function($key) {
    TaskController::delete($key);
  });

  $routes->get('/task/show/:key', function($key){
    TaskController::show($key);
  });

  $routes->get('/task/add', function(){
    TaskController::add();
  });
  
  $routes->get('/task/modify/:key', function($key){
    TaskController::modify($key);
  });


 
