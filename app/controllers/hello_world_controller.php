<?php

  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('suunnitelmat/login.html');
    }

    public static function sandbox(){
      // Testaa koodiasi täällä
      View::make('helloworld.html');
    }

    public static function tasklist(){
      // Testaa koodiasi täällä
      View::make('suunnitelmat/tasklist.html');
    }

    public static function task(){
      // Testaa koodiasi täällä
      View::make('suunnitelmat/task.html');
    }
  }
