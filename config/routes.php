<?php

  $routes->get('/', function() {
    TaskController::tasklist();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/task', function() {
    TaskController::tasklist();
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
  $routes->post('/task', function(){
    TaskController::store();
});

 
