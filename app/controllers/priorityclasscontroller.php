<?php
/**
*Kontrolleriluokka prioriteettiluokkien käsittelyä varten
*
*Sisältää metodit:
*
*priorityclasslist(): prioriteettilistan näyttäminen
*
*delete($key): poisto
*
*add(): lisäyssivun näyttäminen
*
*modify($key): muokkaussivun näyttäminen
*
*store($type): tietueen lisäys tai muokkaus, type- parametri määrää kumpi

*/


class PriorityClassController extends BaseController{
	public static function priorityclasslist(){    
    	self::check_logged_in();
    	$user = self::get_user_logged_in();			
    		
    	$priorityclasses = PriorityClass::all();    
    	View::make('priorityclass/priorityclasslist.html', array('priorityclasses' => $priorityclasses, 'user_logged_in' => $user));
   			
  	} 

  	public static function delete($key){  		 		

  		
  		$errors = PriorityClass::delete($key);
  		Redirect::to('/priorityclass',array('errors' => $errors));
    }

    public static function add(){     	
    	View::make('priorityclass/priorityclass_new.html');
  	}  

  	public static function modify($key){    
    	
    	$priorityclass = PriorityClass::find($key);

    	$attributes = array(
	      'PriorityClassKey' => $priorityclass->PriorityClassKey,
	      'PriorityClassName' => $priorityclass->PriorityClassName,
	      'SortOrder' => $priorityclass->SortOrder
	      );

    	View::make('priorityclass/priorityclass_modify.html', array( 'attributes' => $attributes));
  	} 




  	public static function store($type){
    
	    $params = $_POST;  

	    $attributes = array(
	      'PriorityClassName' => $params['PriorityClassName'],
	      'SortOrder' => $params['SortOrder']

	    );

	    if (empty($params['PriorityClassKey'])==false) {
	    	$attributes['PriorityClassKey'] = $params['PriorityClassKey'];
	    }

	    $priorityclass = new PriorityClass($attributes);

	    //Kint::dump($params);
	    $errors = $priorityclass->errors();

	    //Kint::dump($errors);


 		if(count($errors) == 0){
    	    if ($type == 'insert') {
    	    	$priorityclass->save();
    	    } else {
    	    	$priorityclass->PriorityClassKey = $params['PriorityClassKey']; 
    	    	$priorityclass->update();
    	    }
    	       
	    	Redirect::to('/priorityclass');    	    
	    } else {

	    	if ($type == 'insert') {
	    		View::make('priorityclass/priorityclass_new.html', array('errors' => $errors, 'attributes' => $attributes));
	    	} else {
	    		View::make('priorityclass/priorityclass_modify.html', array('errors' => $errors, 'attributes' => $attributes));
	    	}
	    }
  }
}