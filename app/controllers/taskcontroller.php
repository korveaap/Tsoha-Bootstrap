<?php
class TaskController extends BaseController{
	public static function tasklist(){    
    	$tasks = Task::all();    
    	View::make('task/tasklist.html', array('tasks' => $tasks));
  	} 

  	public static function show($key){    
    	$task = Task::find($key);    
    	View::make('task/task.html', array('task' => $task));
  	} 

  	public static function add(){ 
    	$priorityclasses = PriorityClass::all();
    	$taskclasses = TaskClass::all();
    	View::make('task/task_new.html', array('priorityclasses'=>$priorityclasses, 'taskclasses'=>$taskclasses));
  	}  

  	public static function delete($key){
  		Task::delete($key);
  		Redirect::to('/task');
    }

  	public static function store(){
    
	    $params = $_POST;    
	    $task = new Task(array(
	      'TaskName' => $params['TaskName'],
	      'TaskDescription' => $params['TaskDescription'],
	      'PriorityClassKey' => $params['PriorityClassKey'],
	      'TaskClasses' => $params['TaskClassKey']
	    ));

	    //Kint::dump($params);
	    $task->save();

	    
	    Redirect::to('/task');
  }

}