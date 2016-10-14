<?php
/**
*Malliluokka tehtäväluokkien käsittelyä varten
*
*Sisältää metodit:
*
*all() - kaikkien tehtäväluokkien haku tietokannasta
*
*save() - uuden tehtäväluokan luonti tietokantaan 
*
*delete() - tehtäväluokan poisto
*
*validate_taskclassname() - tehtäväluokan nimen pituuden validointi
*
*validate_existing() - tehtäväluokan nimen olemassaolon validointi
*/


class TaskClass extends BaseModel{
  public $TaskClassKey, $TaskClassName, $TaskClassDescription, $PersonKey;  
  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array('validate_taskclassname', 'validate_existing');
  }
  public static function all(){
    
    $query = DB::connection()->prepare('SELECT taskclasskey, taskclassname, taskclassdescription 
                                                
                                        FROM TaskClass 
                                        WHERE PersonKey = :PersonKey

                                        ORDER BY taskclassname'); 

                                         
    $query->execute(array('PersonKey' => $_SESSION['user']));    
    $rows = $query->fetchAll();
    $taskclasses = array();

    
    foreach($rows as $row){
         $taskclasses[] = new TaskClass(array(
        'TaskClassKey' => $row['taskclasskey'],        
        'TaskClassName' => $row['taskclassname'],
        'TaskClassDescription' => $row['taskclassdescription']      
        
      ));
    }

    return $taskclasses;
  }

  public function save(){
    
    $query = DB::connection()->prepare('INSERT INTO TaskClass (TaskClassName, TaskClassDescription, PersonKey) VALUES (:TaskClassName, :TaskClassDescription, :PersonKey) RETURNING TaskClassKey ');
    
    $query->execute(array('TaskClassName' => $this->TaskClassName, 'TaskClassDescription' => $this->TaskClassDescription, 'PersonKey' => $_SESSION['user']));

    $row = $query->fetch();
    $this->TaskClassKey = $row['taskclasskey'];    
    
    
  }

  public function delete($TaskClassKey) {
      
      $query_check =  DB::connection()->prepare('SELECT count(*) as cnt from taskclassoftask where TaskClassKey = :TaskClassKey');
      $query_check->execute(array('TaskClassKey' => $TaskClassKey));

      $row = $query_check->fetch();



      $errors = array();
      if($row['cnt']!=0){
        $errors[] = 'Tehtäväluokka on käytössä tehtävällä, ei saa poistaa!';
        return $errors;
      }
      

      $query = DB::connection()->prepare('DELETE FROM TaskClass WHERE TaskClassKey = :TaskClassKey');
      $query->execute(array('TaskClassKey' => $TaskClassKey));
  }

  public function validate_taskclassname() {
    
    $errors = array();
    if($this->validate_string($this->TaskClassName)==true){
      $errors[] = 'Tehtäväluokan nimi ei saa olla tyhjä!';
    }
    return $errors;
      
  }

  public function validate_existing() {
      $errors = array();
      
      
      $query_check =  DB::connection()->prepare('SELECT count(*) as cnt from TaskClass where TaskClassName = :TaskClassName and PersonKey = :PersonKey ');
      $query_check->execute(array('TaskClassName' => $this->TaskClassName, 'PersonKey' => $_SESSION['user']));
      

      $row = $query_check->fetch();

      if($row['cnt']!=0){
          $errors[] = 'Tehtäväluokan nimi on jo olemassa!'; 
      }

      

      return $errors;
   }

}