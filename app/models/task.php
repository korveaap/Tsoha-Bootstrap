
<?php
/**
*Malliluokka tehtävien käsittelyä varten
*
*Sisältää metodit:
*
*all() - kaikkien tehtävien haku tietokannasta
*
*find($TaskKey) - yhden tehtävän haku tietokannasta
*
*save() - uuden tehtävän luonti tietokantaan 
*
*update() - tehtävän päivittäminen
*
*delete() - tehtävän poisto
*
*validatetaskname() - tehtävän nimen validointi
*
*validate_taskclass() - tehtäväluokan validointi
*
*validate_priorityclass() - prioriteettiluokan validointi
*/


class Task extends BaseModel{
  
  
  public $TaskKey, $PriorityClassKey, $TaskName, $TaskDescription, $PriorityClassName,$TaskClassName,$TaskClassKey,$PersonKey,$TaskClasses;  
  public function __construct($attributes){
    parent::__construct($attributes);
    $this->validators = array('validate_taskname', 'validate_taskclass','validate_priorityclass');
  }

  public static function all(){
    
    $query = DB::connection()->prepare('SELECT t.TaskKey as taskkey, t.TaskName as taskname, t.PriorityClassKey as priorityclasskey, pc.PriorityClassName as priorityclassname,
                                               array_to_string(array_agg(tc.TaskClassName),\',\') as taskclassname  
                                                
                                        FROM Task t 
                                        JOIN PriorityClass pc ON t.PriorityClassKey = pc.PriorityClassKey
                                        JOIN TaskClassOfTask tct on tct.TaskKey = t.TaskKey  
                                        JOIN TaskClass tc on tc.TaskClassKey = tct.TaskClassKey
                                        WHERE t.PersonKey = :PersonKey
                                        GROUP BY t.TaskKey, t.TaskName, t.PriorityClassKey, pc.PriorityClassName,pc.SortOrder
                                        ORDER BY pc.sortorder'); 

                                         
    $query->execute(array('PersonKey' => $_SESSION['user']));    
    $rows = $query->fetchAll();
    $tasks = array();

    
    foreach($rows as $row){
         $tasks[] = new Task(array(
        'TaskKey' => $row['taskkey'],
        'TaskName' => $row['taskname'],
        'PriorityClassName' => $row['priorityclassname'],
        'PriorityClassKey' => $row['priorityclasskey'],
        'TaskClassName' => $row['taskclassname']       
        
      ));
    }

    return $tasks;
  }

  public static function find($TaskKey){
    $query = DB::connection()->prepare('SELECT t.TaskKey as taskkey, t.TaskName as taskname, t.taskdescription as taskdescription, t.PriorityClassKey as priorityclasskey, 
                                               pc.PriorityClassName as priorityclassname,
                                               array_to_string(array_agg(tc.TaskClassName),\',\') as taskclassname, array_agg(tc.TaskClassKey) as taskclasses                                                 
                                        FROM Task t 
                                        JOIN PriorityClass pc ON t.PriorityClassKey = pc.PriorityClassKey
                                        JOIN TaskClassOfTask tct on tct.TaskKey = t.TaskKey  
                                        JOIN TaskClass tc on tc.TaskClassKey = tct.TaskClassKey
                                        WHERE t.TaskKey = :TaskKey 
                                        GROUP BY t.TaskKey, t.TaskName, t.PriorityClassKey, pc.PriorityClassName,pc.SortOrder, t.taskdescription');
                                        
                                        
    $query->execute(array('TaskKey' => $TaskKey));
    $row = $query->fetch();

    if($row){
      $task = new Task(array(        
        'TaskKey' => $row['taskkey'],
        'TaskName' => $row['taskname'],
        'PriorityClassKey' => $row['priorityclasskey'],
        'PriorityClassName' => $row['priorityclassname'],
        'TaskDescription' => $row['taskdescription'],
        'TaskClassName' => $row['taskclassname'],
        'TaskClasses' => $row['taskclasses']
      ));

      return $task;
    }

    return null;
  }

  


  
  public function save(){
    
    $query_task = DB::connection()->prepare('INSERT INTO Task (TaskName, PriorityClassKey, TaskDescription, PersonKey) VALUES (:TaskName, :PriorityClassKey, :TaskDescription, :PersonKey) RETURNING TaskKey ');
    
    $query_task->execute(array('TaskName' => $this->TaskName, 'PriorityClassKey' => $this->PriorityClassKey, 'TaskDescription' => $this->TaskDescription, 'PersonKey' => $_SESSION['user']));

    $row = $query_task->fetch();
    $this->TaskKey = $row['taskkey'];

    foreach ($this->TaskClasses as $tckey) {
      $query_taskclass = DB::connection()->prepare('INSERT INTO TaskClassOfTask (TaskKey, TaskClassKey) VALUES (:TaskKey, :TaskClassKey) ');
      $query_taskclass->execute(array('TaskKey' => $this->TaskKey, 'TaskClassKey' => $tckey)); 
    }

    
    
  }

  public function update(){
    
    $query_task = DB::connection()->prepare('UPDATE Task Set TaskName = :TaskName, PriorityClassKey = :PriorityClassKey , TaskDescription = :TaskDescription WHERE TaskKey = :TaskKey ');
    
    $query_task->execute(array('TaskName' => $this->TaskName, 'PriorityClassKey' => $this->PriorityClassKey, 'TaskDescription' => $this->TaskDescription, 'TaskKey'=>$this->TaskKey));

    
     
    $query_delete_taskclass = DB::connection()->prepare('DELETE FROM TaskClassOfTask WHERE TaskKey = :TaskKey');
    $query_delete_taskclass->execute(array('TaskKey'=>$this->TaskKey));


    foreach ($this->TaskClasses as $tckey) {
      $query_taskclass = DB::connection()->prepare('INSERT INTO TaskClassOfTask (TaskKey, TaskClassKey) VALUES (:TaskKey, :TaskClassKey) ');
      $query_taskclass->execute(array('TaskKey' => $this->TaskKey, 'TaskClassKey' => $tckey)); 
    }

    
    
  }

  public function delete($TaskKey) {

      $query_taskclass = DB::connection()->prepare('DELETE FROM TaskClassOfTask WHERE TaskKey = :TaskKey');
      $query_taskclass->execute(array('TaskKey' => $TaskKey));

      $query_task = DB::connection()->prepare('DELETE FROM Task WHERE TaskKey = :TaskKey');
      $query_task->execute(array('TaskKey' => $TaskKey));
  }

  public function validate_taskname() {
    
    $errors = array();
    if($this->validate_string($this->TaskName)==true){
      $errors[] = 'Tehtävän otsikko ei saa olla tyhjä!';
    }
    return $errors;
      
  }

  public function validate_taskclass() {
    
    $errors = array();
      if($this->validate_selection($this->TaskClasses)){
      $errors[] = 'Vähintään yksi tehtäväluokka pitää olla valittuna!';
    }
    return $errors;
      
  }

  public function validate_priorityclass() {
    
    $errors = array();
    if($this->validate_string($this->PriorityClassKey)==true){
      $errors[] = 'Prioriteettiluokka pitää olla valittuna!';
    }
    return $errors;
  }


}