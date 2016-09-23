
<?php

class Task extends BaseModel{
  
  
  public $TaskKey, $PriorityClassKey, $TaskName, $TaskDescription, $PriorityClassName;  
  public function __construct($attributes){
    parent::__construct($attributes);
  }

  public static function all(){
    
    $query = DB::connection()->prepare('SELECT t.TaskKey as taskkey, t.TaskName as taskname, t.PriorityClassKey as priorityclasskey, pc.PriorityClassName as priorityclassname 
                                                
                                        FROM Task t 
                                        JOIN PriorityClass pc 
                                        ON t.PriorityClassKey = pc.PriorityClassKey
                                        ORDER BY pc.sortorder');    
    $query->execute();    
    $rows = $query->fetchAll();
    $tasks = array();

    
    foreach($rows as $row){
         $tasks[] = new Task(array(
        'TaskKey' => $row['taskkey'],
        'TaskName' => $row['taskname'],
        'PriorityClassName' => $row['priorityclassname'],
        'PriorityClassKey' => $row['priorityclasskey']       
        
      ));
    }

    return $tasks;
  }

  public static function find($TaskKey){
    $query = DB::connection()->prepare('SELECT t.TaskKey as taskkey, t.TaskName as taskname, t.taskdescription as taskdescription, t.PriorityClassKey as priorityclasskey, 
                                               pc.PriorityClassName as priorityclassname  
                                        FROM Task t
                                        JOIN PriorityClass pc 
                                        ON t.PriorityClassKey = pc.PriorityClassKey 
                                        WHERE TaskKey = :TaskKey LIMIT 1');
    $query->execute(array('TaskKey' => $TaskKey));
    $row = $query->fetch();

    if($row){
      $task = new Task(array(        
        'TaskKey' => $row['taskkey'],
        'TaskName' => $row['taskname'],
        'PriorityClassKey' => $row['priorityclasskey'],
        'PriorityClassName' => $row['priorityclassname'],
        'TaskDescription' => $row['taskdescription']      
      ));

      return $task;
    }

    return null;
  }

  public static function store(){
    
    $params = $_POST;    
    $task = new Task(array(
      'TaskName' => $params['TaskName'],
      'TaskDescription' => $params['TaskDescription'],
      'PriorityClassKey' => $params['PriorityClassKey'],
      'TaskClassName' => $params['TaskClassName']
    ));

    
    $task->save();

    
    Redirect::to('/task/');
  }


  
  public function save(){
    
    $query = DB::connection()->prepare('INSERT INTO Task (TaskName, PriorityClassKey, TaskDescription, PersonKey) VALUES (:TaskName, :PriorityClassKey, :TaskDescription, :PersonKey) ');
    
    $query->execute(array('TaskName' => $this->TaskName, 'PriorityClassKey' => $this->PriorityClassKey, 'TaskDescription' => $this->TaskDescription, 'PersonKey' => 1));
    
  }


}