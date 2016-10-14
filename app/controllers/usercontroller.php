<?php
/**
*Kontrolleriluokka käyttäjien käsittelyä varten
*
*Sisältää metodit:
*
*login(): kirjautumissivun näyttäminen
*
*logout(): uloskirjautuminen
*
*handle_login(): käyttäjän autentikointi ja tehtävälistan näyttäminen
*/


class UserController extends BaseController{
	public static function login(){      
      View::make('/login.html');
  	}

	public static function logout(){      
      $_SESSION['user'] = null;
      Redirect::to('/login', array('message' => 'Olet kirjautunut ulos!'));
  	}

  	public static function handle_login(){
    	$params = $_POST;

	    $user = User::authenticate($params['username'], $params['password']);

	    if(!$user){
	      View::make('/login.html', array('error' => 'Väärä käyttäjätunnus tai salasana!', 'username' => $params['username']));
	    }else{
	      $_SESSION['user'] = $user->PersonKey;

	      Redirect::to('/task');
	    }
  }
}