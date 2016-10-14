<?php
/**
*Malliluokka prioriteettiluokkien käsittelyä varten
*
*Sisältää metodit:
*
*all() - kaikkien prioriteettiluokkien haku tietokannasta
*
*find($TaskKey) - yhden prioriteettiluokkan haku tietokannasta
*
*save() - uuden prioriteettiluokkan luonti tietokantaan 
*
*update() - prioriteettiluokkan päivittäminen
*
*delete() - prioriteettiluokkan poisto
*
*validate_priorityclassname() - prioriteettiluokan nimen pituuden validointi
*
*validate_sortorder_numeric() - järjestysnumeron numeerisuuden validointi
*
*validate_existing() - prioriteettiluokan nimen olemassaolon validointi
*/


class PriorityClass extends BaseModel{
  public $PriorityClassKey, $PriorityClassName, $SortOrder, $PersonKey;  
  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array('validate_priorityclassname', 'validate_sortorder_numeric', 'validate_existing');
  }
  public static function all(){
    
    $query = DB::connection()->prepare('SELECT priorityclasskey, priorityclassname, sortorder 
                                                
                                        FROM PriorityClass where PersonKey = :PersonKey
                                        
                                        ORDER BY sortorder'); 

                                         
    $query->execute(array('PersonKey' => $_SESSION['user']));    
    $rows = $query->fetchAll();
    $priorityclasses = array();

    
    foreach($rows as $row){
         $priorityclasses[] = new PriorityClass(array(
        'PriorityClassKey' => $row['priorityclasskey'],        
        'PriorityClassName' => $row['priorityclassname'],
        'SortOrder' => $row['sortorder']      
        
      ));
    }

    return $priorityclasses;
  }

  public static function find($PriorityClassKey){
    
    $query = DB::connection()->prepare('SELECT priorityclasskey, priorityclassname, sortorder 
                                                
                                        FROM PriorityClass where priorityclasskey = :PriorityClassKey
                                        

                                        ORDER BY sortorder'); 

                                         
    $query->execute(array('PriorityClassKey' => $PriorityClassKey));    
    $row = $query->fetch();
    

    if($row){    
         $priorityclass = new PriorityClass(array(
        'PriorityClassKey' => $row['priorityclasskey'],        
        'PriorityClassName' => $row['priorityclassname'],
        'SortOrder' => $row['sortorder']      
        
      ));
      return $priorityclass;   
    }

    return $null;
  }

  public function delete($PriorityClassKey) {
      
      $query_check =  DB::connection()->prepare('SELECT count(*) as cnt from task where PriorityClassKey = :PriorityClassKey');
      $query_check->execute(array('PriorityClassKey' => $PriorityClassKey));

      $row = $query_check->fetch();



      $errors = array();
      if($row['cnt']!=0){
        $errors[] = 'Prioriteettiluokka on käytössä tehtävällä, ei saa poistaa!';
        return $errors;
      }
      

      $query = DB::connection()->prepare('DELETE FROM PriorityClass WHERE PriorityClassKey = :PriorityClassKey');
      $query->execute(array('PriorityClassKey' => $PriorityClassKey));
  }

  public function save(){
    
    $query = DB::connection()->prepare('INSERT INTO PriorityClass (PriorityClassName, SortOrder, PersonKey) VALUES (:PriorityClassName, :SortOrder, :PersonKey) RETURNING PriorityClassKey ');
    
    $query->execute(array('PriorityClassName' => $this->PriorityClassName, 'SortOrder' => $this->SortOrder, 'PersonKey' => $_SESSION['user']));

    $row = $query->fetch();
    $this->PriorityClassKey = $row['priorityclasskey'];    
    
    
  }

  public function update(){
    
    $query_task = DB::connection()->prepare('UPDATE PriorityClass Set PriorityClassName = :PriorityClassName, SortOrder = :SortOrder  WHERE PriorityClassKey = :PriorityClassKey ');
    
    $query_task->execute(array('PriorityClassName' => $this->PriorityClassName, 'SortOrder' => $this->SortOrder,  'PriorityClassKey'=>$this->PriorityClassKey));     
    

    
  }

  public function validate_priorityclassname() {
    
    $errors = array();
    if($this->validate_string($this->PriorityClassName)==true){
      $errors[] = 'Prioriteettiluokan nimi ei saa olla tyhjä!';
    }
    return $errors;
      
  }
   
   public function validate_sortorder_numeric() {
      $errors = array();
      if($this->validate_integer($this->SortOrder)==true){
        $errors[] = 'Lajittelujärjestysnumeron on oltava kokonaisluku!';
      }
      return $errors;
   } 

   public function validate_existing() {
      $errors = array();
      
      if (is_null($this->PriorityClassKey)) {
        $query_check =  DB::connection()->prepare('SELECT count(*) as cnt from PriorityClass where PriorityClassName = :PriorityClassName and PersonKey = :PersonKey');
        $query_check->execute(array('PriorityClassName' => $this->PriorityClassName, 'PersonKey' => $_SESSION['user']));
      } else {
        $query_check =  DB::connection()->prepare('SELECT count(*) as cnt from PriorityClass where PriorityClassName = :PriorityClassName and PersonKey = :PersonKey and PriorityClassKey <> :PriorityClassKey');
        $query_check->execute(array('PriorityClassName' => $this->PriorityClassName, 'PersonKey' => $_SESSION['user'], 'PriorityClassKey' => $this->PriorityClassKey));
      }

      $row = $query_check->fetch();

      if($row['cnt']!=0){
          $errors[] = 'Prioriteettiluokan nimi on jo olemassa!'; 
      }
      if($this->validate_integer($this->SortOrder)==false){
        if (is_null($this->PriorityClassKey)) {
          $query_check =  DB::connection()->prepare('SELECT count(*) as cnt from PriorityClass where SortOrder = :SortOrder and PersonKey = :PersonKey');
          $query_check->execute(array('SortOrder' => $this->SortOrder, 'PersonKey' => $_SESSION['user']));
        } else {
          $query_check =  DB::connection()->prepare('SELECT count(*) as cnt from PriorityClass where SortOrder = :SortOrder and PersonKey = :PersonKey and PriorityClassKey <> :PriorityClassKey');
          $query_check->execute(array('SortOrder' => $this->SortOrder, 'PersonKey' => $_SESSION['user'], 'PriorityClassKey' => $this->PriorityClassKey));
        }
        $row = $query_check->fetch();

        if($row['cnt']!=0){
            $errors[] = 'Lajittelujärjestysnumero on jo olemassa!'; 
        }
      }

      return $errors;
   } 



  
}