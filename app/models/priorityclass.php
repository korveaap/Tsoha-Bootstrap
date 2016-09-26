<?php

class PriorityClass extends BaseModel{
  public $PriorityClassKey, $PriorityClassName, $SortOrder, $PersonKey;  
  public function __construct($attributes){
    parent::__construct($attributes);
  }
  public static function all(){
    
    $query = DB::connection()->prepare('SELECT priorityclasskey, priorityclassname, sortorder 
                                                
                                        FROM PriorityClass 
                                        
                                        ORDER BY sortorder'); 

                                         
    $query->execute();    
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
}