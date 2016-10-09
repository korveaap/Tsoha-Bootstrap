<?php

  class BaseModel{
    // "protected"-attribuutti on käytössä vain luokan ja sen perivien luokkien sisällä
    protected $validators;

    public function __construct($attributes = null){
      // Käydään assosiaatiolistan avaimet läpi
      
      foreach($attributes as $attribute => $value){
        // Jos avaimen niminen attribuutti on olemassa...
        if(property_exists($this, $attribute)){
          // ... lisätään avaimen nimiseen attribuuttin siihen liittyvä arvo
          $this->{$attribute} = $value;
        }
      }
    }

    public function errors(){
      // Lisätään $errors muuttujaan kaikki virheilmoitukset taulukkona
      $errors = array();
      

      foreach($this->validators as $validator){
        // Kutsu validointimetodia tässä ja lisää sen palauttamat virheet errors-taulukkoon        ;
        $errors = array_merge($errors,$this->{$validator}());
      }

      return $errors;
    }


    public function validate_string($string) {
      
      if(preg_replace('/\s/', '', $string) == '' || $string == null ){
          
        return true;  
      }
      return false;
    }

    public function validate_integer($string) {
      if (is_numeric($string)) {
        if(is_integer($string+1)){
            
          return false;  
        }
      }
      return true;
    }

    public function validate_selection($arr) {
      
      if(count($arr)==0){
          
        return  true;
      }
      return false;
    }


  }
