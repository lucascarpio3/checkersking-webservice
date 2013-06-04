<?php
    class Game{
    	
		public static function getUsers(){
			DB::init();
			session_start();
			$result = DB::query('SELECT id,nome,avatarlink,status FROM jogador where status <> 0 and id <> '.$_SESSION['usuarioID'].' ORDER BY nome');
		
			$users = array();
			while($user = $result->fetch_object()){
				$users[] = $user;
			}
			return array(
				'users' => $users
			);
		}
		
		public static function logOut(){
			DB::init();
			session_start();
			DB::query("UPDATE jogador SET status = 0 WHERE id = ".$_SESSION['usuarioID']);
			session_destroy();
			unset($_SESSION['usuarioID'],
				  $_SESSION['usuarioNome'],
				  $_SESSION['usuarioLogin'],
				  $_SESSION['usuarioSenha'],
				  $_SESSION['jogo'],
				  $_SESSION['jogada'],
				  $_SESSION['userView']
				 );
		}
	}
?>