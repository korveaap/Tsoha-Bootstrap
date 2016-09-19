<?php

  $routes->get('/', function() {
    HelloWorldController::index();
  });

  $routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
  });

  $routes->get('/tasklist', function() {
    HelloWorldController::tasklist();
  });

  $routes->get('/task', function() {
    HelloWorldController::task();
  });
