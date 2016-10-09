<?php
class TaskController extends BaseController{
	public static function tasklist(){    
    	self::check_logged_in();
    	$user = self::get_user_logged_in();			
    		
    	$tasks = Task::all();    
    	View::make('task/tasklist.html', array('tasks' => $tasks, 'user_logged_in' => $user));
   			
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

  	public static function modify($key){    
    	$priorityclasses = PriorityClass::all();
    	$taskclasses = TaskClass::all();
    	$task = Task::find($key);

    	$attributes = array(
	      'TaskKey' => $task->TaskKey,
	      'TaskName' => $task->TaskName,
	      'TaskDescription' => $task->TaskDescription,
	      'PriorityClassKey' => $task->PriorityClassKey,
	      'TaskClasses' =>$task->TaskClasses
	      );

    	View::make('task/task_modify.html', array( 'attributes' => $attributes, 'priorityclasses'=>$priorityclasses, 'taskclasses'=>$taskclasses));
  	} 

  	public static function delete($key){
  		Task::delete($key);
  		Redirect::to('/task');
    }

  	public static function store($type){
    
	    $params = $_POST; 
	    //$taskclasses[] = array(); 
	    if (empty($params['TaskClassKey'])) {
	    	//$taskclasses[] = $params['TaskClassKey'];
	    	$params['TaskClassKey'] = array();
	    }



	    $attributes = array(
	      'TaskName' => $params['TaskName'],
	      'TaskDescription' => $params['TaskDescription'],
	      'PriorityClassKey' => $params['PriorityClassKey'],
	      'TaskClasses' => $params['TaskClassKey']


	    );
	    $task = new Task($attributes);

	    if (empty($params['TaskKey'])==false) {
	    	$attributes['TaskKey'] = $params['TaskKey'];
	    }

	    //Kint::dump($params);
	    $errors = $task->errors();

	    //Kint::dump($errors);


 		if(count($errors) == 0){
    	    if ($type == 'insert') {
    	    	$task->save();
    	    } else {
    	    	$task->TaskKey = $params['TaskKey']; 
    	    	$task->update();
    	    }	    
	    	Redirect::to('/task');
	    } else {


	    	$priorityclasses = PriorityClass::all();
    		$taskclasses = TaskClass::all();
	    	if ($type == 'insert') {
    	    	View::make('task/task_new.html', array('errors' => $errors, 'attributes' => $attributes, 'priorityclasses'=>$priorityclasses, 'taskclasses'=>$taskclasses));
    	    } else {
    	    	
    	    	View::make('task/task_modify.html', array('errors' => $errors, 'attributes' => $attributes, 'priorityclasses'=>$priorityclasses, 'taskclasses'=>$taskclasses));
    	    
    	    }
	    }
  }

}