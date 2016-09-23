<?php
   
  class HelloWorldController extends BaseController{

    public static function index(){
      // make-metodi renderöi app/views-kansiossa sijaitsevia tiedostoja
   	  View::make('suunnitelmat/login.html');
    }

    public static function sandbox(){
      $t1 = Task::find(1);
      $tasks = Task::all();
      // Kint-luokan dump-metodi tulostaa muuttujan arvon
      Kint::dump($tasks);
      Kint::dump($t1);
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
