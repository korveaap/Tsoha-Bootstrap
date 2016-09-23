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
    	View::make('task/task_new.html');
  	}  

}