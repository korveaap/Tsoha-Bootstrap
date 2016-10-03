<?php

class TaskClass extends BaseModel{
  public $TaskClassKey, $TaskClassName, $TaskClassDescription, $PersonKey;  
  public function __construct($attributes){
    parent::__construct($attributes);
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
}