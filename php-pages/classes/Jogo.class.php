<?php

	class Jogo{
		private static $numLines;
		private static $instance;
		private $id;
		private $jogador2;
		private function __construct($jogador2){
			
			settype($jogador2, "integer");
			$this->jogador2 = $jogador2;
			session_start();
			$con = mysql_connect(DB::$dbOptions['db_host'],
								 DB::$dbOptions['db_user'],
								 DB::$dbOptions['db_pass']);
			if (!$con){
  				die('Could not connect: ' . mysql_error());
			}
			mysql_select_db(DB::$dbOptions['db_name'], $con);
			mysql_query("INSERT INTO `jogo`() VALUES ()");
			$this->id = mysql_insert_id();
			echo "id - $this->id ou ".mysql_insert_id();
			mysql_close($con);
			DB::init();
			DB::query("INSERT INTO jogador_jogo (jogoid,jogadorid,status) VALUES ($this->id,".$_SESSION['usuarioID'].",0)");
			DB::query("INSERT INTO jogador_jogo (jogoid,jogadorid,status) VALUES ($this->id,$jogador2,1)");
		}
		
		public static function init($jogador2){

			if(self::$instance instanceof self){
				return false;
			}
				
			self::$instance = new self($jogador2);
			return mysql_insert_id();
		}
		
		public static function aceitarDesafio($jogoID){
			session_start();
			DB::init();
			//STATUS 1 - JOGO EM ANDAMENTO
			$result = DB::query("update jogo set status = 1 where id = $jogoID");
			if($result){
				$result = DB::query("select jj.jogoid id, jdr.nome oponente from jogador_jogo jj, jogador jdr where jj.jogoid = $jogoID and jdr.id = jj.jogadorid and jj.status = 0");
				$jogoCapturado = $result->fetch_object();
				$_SESSION['jogo']['id'] = $jogoCapturado->id;
				$_SESSION['jogo']['oponent'] = $jogoCapturado->oponente;
				$_SESSION['jogo']['turno_de_jogada'] = 0;
				$result = DB::query("update jogador set status = 2 where id =".$_SESSION['usuarioID']);
			}
		}
		
		public static function recusarDesafio($jogoID){
			DB::init();
			//STATUS 2 - JOGO CANCELADO
			$result = DB::query("update jogo set status = 2 where id = $jogoID");
		}
		
		public static function temJogo($desafiante){
			DB::init();
			@session_start();
			$jogoCapturado = null;
			if($desafiante == 0){ 
				$result = DB::query("select jj.jogoid id,
											jdr.nome oponente
									 from 	jogo jo,
									 		jogador jdr,
									 		jogador_jogo jj
									 where 	jo.id = (select jogoid
									 				from   jogador_jogo
									 				where 	jogadorid = ".$_SESSION['usuarioID']." 
									 				and 	status = 1
									 			   )
									and		jo.status = 0
									and 	jj.jogoid = jo.id
									and 	jdr.id = jj.jogadorid
									and 	jj.status = 0"
									);
				$jogoCapturado = $result->fetch_object();
			}else if($desafiante == 1){
				$result = DB::query("	SELECT jj.jogoid id,
											   jdr.nome oponente
										FROM jogo jo, 
											 jogador jdr,
										 	 jogador_jogo jj
										WHERE jo.id = ( 
														SELECT jogoid
														FROM jogador_jogo
														WHERE jogadorid = ".$_SESSION['usuarioID']."
														AND STATUS =0 
													  )
										AND jo.status =1
										AND jj.jogoid = jo.id
										AND jdr.id = jj.jogadorid
										AND jj.status = 1"
									);
				$jogoCapturado = $result->fetch_object();
				if($jogoCapturado != NULL){
					$_SESSION['jogo']['id'] = $jogoCapturado->id;
					$_SESSION['jogo']['oponent'] = $jogoCapturado->oponente;
					$_SESSION['jogo']['turno_de_jogada'] = 1;
					$result = DB::query("update jogador set status = 2 where id =".$_SESSION['usuarioID']);
				}
			}
			return $jogoCapturado;
		}
		
		public static function setmovimento($jogador,$tipo,$idOrigem,$indDestino){
			//@session_start();
			var_dump($_SESSION['jogo']);
			$fd = @fopen( "../jogos/jogo".$_SESSION['jogo']['id'].".txt", "a+" ) or die( "ops, avise o webmaster, tem ficheiro faltando no servidor");
			fwrite($fd, "$jogador,$tipo,$idOrigem,$indDestino\n");
			@fclose($fd);
		}
		
		public static function getMovimento(){
			@session_start();
			if(!isset($_SESSION['jogada'])){
				$_SESSION['jogada'] = 0;
			}
			$file = "../jogos/jogo".$_SESSION['jogo']['id'].".txt";
			if(file_exists($file) && ($_SESSION['jogada'] < count(file($file)))){
				$_SESSION['jogada'] = count(file($file));
				$data = file($file);
				$line = $data[count($data)-1];
				$jogada = array();
				list ($jogada["jogador"], $jogada["tipo"],$jogada["campoOrigem"],$jogada["campoDestino"]) = @split('[,]', $line);
				if($_SESSION['usuarioID'] != $jogada["jogador"]){
					$jogada["jogador"] 		 =  floor($jogada["jogador"]);
					$jogada["campoOrigem"] 	 =  $jogada["campoOrigem"]{1}.$jogada["campoOrigem"]{2};
					return $jogada;
				}else return NULL;
			}else return NULL;
		}
		
		public static function finalizaJogo($type){
			DB::init();
			session_start();
			if($type == 0){
				DB::query("update jogo set status = 3,vencedor=".$_SESSION['jogo']['oponent']." where id = ".$_SESSION['jogo']['id']);
				DB::query("update jogador set status = 1 where id in (".$_SESSION['usuarioID'].",".$_SESSION['jogo']['oponent'].")");
				unset($_SESSION['jogo']);
			}else if($type == 1 && DB::query("select id from jogo where id = ".$_SESSION['jogo']['id']." and status = 3")->fetch_object() != NULL){
				unset($_SESSION['jogo']);
			}
		}
	}
?>