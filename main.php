<?php
/**
* Telegram Bot example for Italian Museums of DBUnico Mibact Lic. CC-BY
* @author Francesco Piero Paolicelli @piersoft
*/
//include("settings_t.php");
include("Telegram.php");

class mainloop{
const MAX_LENGTH = 4096;
function start($telegram,$update)
{

	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");
	//$data=new getdata();
	// Instances the class

	/* If you need to manually take some parameters
	*  $result = $telegram->getData();
	*  $text = $result["message"] ["text"];
	*  $chat_id = $result["message"] ["chat"]["id"];
	*/


	$text = $update["message"] ["text"];
	$chat_id = $update["message"] ["chat"]["id"];
	$user_id=$update["message"]["from"]["id"];
	$location=$update["message"]["location"];
	$reply_to_msg=$update["message"]["reply_to_message"];

	$this->shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg);
	$db = NULL;

}

//gestisce l'interfaccia utente
 function shell($telegram,$text,$chat_id,$user_id,$location,$reply_to_msg)
{
	date_default_timezone_set('Europe/Rome');
	$today = date("Y-m-d H:i:s");

	if ($text == "/start" || $text == "Informazioni") {
		$reply = "Benvenuto. Per ricercare uno Farmaco censito dalla Agenzia Italiana del Farmaco (AIFA), clicca su ClasseA, ClasseC o ClasseH per avere le istruzioni. Verrà interrogato il DataBase openData utilizzabile con licenza CC-BY presente su http://www.agenziafarmaco.gov.it/it/content/dati-sulle-liste-dei-farmaci-open-data . In qualsiasi momento scrivendo /start ti ripeterò questo messaggio di benvenuto.\nQuesto bot, non ufficiale e non collegato con l' AIFA, è stato realizzato da @piersoft.";
		$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
		$telegram->sendMessage($content);
		$log=$today. ";new chat started;" .$chat_id. "\n";
		$this->create_keyboard_temp($telegram,$chat_id);

		exit;
		}
	elseif ($text == "ClasseA") {
				$reply = "Scrivi la parola da cercare anteponendo i caratteri a?, ad esempio: a?clavulanico.\nSe invece vuoi fare la ricerca per Principio attivo usa p?a per esempio p?aAripiprazolo";
				$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
				$log=$today. ";new chat started;" .$chat_id. "\n";
	//			$this->create_keyboard_temp($telegram,$chat_id);
exit;

}elseif ($text == "ClasseH") {
			$reply = "Scrivi la parola da cercare anteponendo i caratteri h?, ad esempio: h?clavulanico.\nSe invece vuoi fare la ricerca per Principio attivo usa p?a per esempio p?hAripiprazolo";
			$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
			$log=$today. ";new chat started;" .$chat_id. "\n";
//			$this->create_keyboard_temp($telegram,$chat_id);
exit;

}elseif ($text == "ClasseC") {
			$reply = "Scrivi la parola da cercare anteponendo i caratteri c?, ad esempio: c?clavulanico.\nSe invece vuoi fare la ricerca per Principio attivo usa p?c per esempio p?cAripiprazolo";
			$content = array('chat_id' => $chat_id, 'text' => $reply,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);
			$log=$today. ";new chat started;" .$chat_id. "\n";
//			$this->create_keyboard_temp($telegram,$chat_id);
exit;

}elseif($location!=null)
		{

		//	$this->location_manager($telegram,$user_id,$chat_id,$location);
			exit;

		}
//elseif($text !=null)

		elseif(strpos($text,'/') === false){
			$img = curl_file_create('aifa.png','image/png');
			$contentp = array('chat_id' => $chat_id, 'photo' => $img);
			$telegram->sendPhoto($contentp);
			if(strpos($text,'a?') !== false){
				$text=str_replace("a?","",$text);
				if ($text==""){
							$location="Inserire almeno una parola";
							$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
							$telegram->sendMessage($content);
						}
				$location="Sto cercando i farmaci di Classe A con denominazione: ".$text;
				$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
				$text=str_replace(" ","%20",$text);
			//	$text=strtoupper($text);
			$inizio=2;
			$homepage ="";
			$text=strtoupper($text);
			  $urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20B%20LIKE%20%27%25";
			  $urlgd .=$text;
			  $urlgd .="%25%27&key=18EDVuGRwVckXvrxXp1RvTXuoNDyIEuwfi2rWaJEvcVA&gid=504724563";
				sleep (1);
				$csv = array_map('str_getcsv',file($urlgd));
			//var_dump($csv[1][0]);
				$count = 0;
				foreach($csv as $data=>$csv1){
					$count = $count+1;
				}
				if ($count ==0 || $count ==1){
							$location="Nessun risultato trovato";
							$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
							$telegram->sendMessage($content);
						}

				for ($i=$inizio;$i<$count;$i++){
					$csv[$i][3]=str_replace(".",",",$csv[$i][3]);
					$homepage .="\n";
					$homepage .="Principio attivo: ".$csv[$i][0]."\n";
					$homepage .="Descrizione: ".$csv[$i][1]."\n";
					$homepage .="Denominazione: ".$csv[$i][2]."\n";
					$homepage .="Prezzo al pubblico: ".$csv[$i][3]."€\n";
					$homepage .="Ditta: ".$csv[$i][4]."\n";
					$homepage .="Codice: ".$csv[$i][5]."\n";
					$homepage .="Codice gruppo equivalenza: ".$csv[$i][6]."\n";
					$homepage .="____________\n";

			}

		}elseif(strpos($text,'p?a') !== false){
				$text=str_replace("p?a","",$text);
				if ($text==""){
							$location="Inserire almeno una parola";
							$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
							$telegram->sendMessage($content);
						}
				$location="Sto cercando i farmaci di Classe A con principio attivo: ".$text;
			  $content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
		   	$text=str_replace(" ","%20",$text);
				$inizio=1;
				$homepage ="";
				//$text=strtoupper($text);
				  $urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20A%20LIKE%20%27%25";
				  $urlgd .=$text;
				  $urlgd .="%25%27&key=18EDVuGRwVckXvrxXp1RvTXuoNDyIEuwfi2rWaJEvcVA&gid=504724563";
					sleep (1);
					$csv = array_map('str_getcsv',file($urlgd));
				//var_dump($csv[1][0]);
					$count = 0;
					foreach($csv as $data=>$csv1){
						$count = $count+1;
					}
					if ($count ==0 || $count ==1){
								$location="Nessun risultato trovato";
								$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
								$telegram->sendMessage($content);
							}

					for ($i=$inizio;$i<$count;$i++){
$csv[$i][3]=str_replace(".",",",$csv[$i][3]);
						$homepage .="\n";
						$homepage .="Principio attivo: ".$csv[$i][0]."\n";
						$homepage .="Descrizione: ".$csv[$i][1]."\n";
						$homepage .="Denominazione: ".$csv[$i][2]."\n";
						$homepage .="Prezzo al pubblico: ".$csv[$i][3]."€\n";
						$homepage .="Ditta: ".$csv[$i][4]."\n";
						$homepage .="Codice: ".$csv[$i][5]."\n";
						$homepage .="Codice gruppo equivalenza: ".$csv[$i][6]."\n";
						$homepage .="____________\n";

				}

			}elseif(strpos($text,'c?') !== false){
				$text=str_replace("c?","",$text);
				if ($text==""){
							$location="Inserire almeno una parola";
							$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
							$telegram->sendMessage($content);
						}
				$location="Sto cercando i farmaci di Classe C con denominazione: ".$text;
			  $content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
		   	$text=str_replace(" ","%20",$text);
				$inizio=1;
				$homepage ="";
				$text=strtoupper($text);
				  $urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20E%20LIKE%20%27%25";
				  $urlgd .=$text;
				  $urlgd .="%25%27&key=18EDVuGRwVckXvrxXp1RvTXuoNDyIEuwfi2rWaJEvcVA&gid=279566657";
					sleep (1);
					$csv = array_map('str_getcsv',file($urlgd));
				//var_dump($csv[1][0]);
					$count = 0;
					foreach($csv as $data=>$csv1){
						$count = $count+1;
					}
					if ($count ==0 || $count ==1){
								$location="Nessun risultato trovato";
								$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
								$telegram->sendMessage($content);
							}

					for ($i=$inizio;$i<$count;$i++){
$csv[$i][8]=str_replace(".",",",$csv[$i][8]);
						$homepage .="\n";
						$homepage .="Farmaco: ".$csv[$i][4]."\n";
						$homepage .="Principio attivo: ".$csv[$i][0]."\n";
						$homepage .="Confezione di riferimento: ".$csv[$i][1]."\n";
						$homepage .="Confezione: ".$csv[$i][5]."\n";
						$homepage .="Prezzo al pubblico: ".$csv[$i][8]."€\n";
						$homepage .="Ditta: ".$csv[$i][6]."\n";;
						$homepage .="Codice gruppo equivalenza: ".$csv[$i][11]."\n";
						$homepage .="____________\n";

				}

			}elseif(strpos($text,'c?p') !== false){
				$text=str_replace("c?p","",$text);
				if ($text==""){
							$location="Inserire almeno una parola";
							$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
							$telegram->sendMessage($content);
						}
				$location="Sto cercando i farmaci di Classe C con principio attivo: ".$text;
			  $content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
		   	$text=str_replace(" ","%20",$text);
				$inizio=1;
				$homepage ="";
			//	$text=strtoupper($text);
				  $urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20A%20LIKE%20%27%25";
				  $urlgd .=$text;
				  $urlgd .="%25%27&key=18EDVuGRwVckXvrxXp1RvTXuoNDyIEuwfi2rWaJEvcVA&gid=279566657";
					sleep (1);
					$csv = array_map('str_getcsv',file($urlgd));
				//var_dump($csv[1][0]);
					$count = 0;
					foreach($csv as $data=>$csv1){
						$count = $count+1;
					}
					if ($count ==0 || $count ==1){
								$location="Nessun risultato trovato";
								$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
								$telegram->sendMessage($content);
							}

					for ($i=$inizio;$i<$count;$i++){
$csv[$i][8]=str_replace(".",",",$csv[$i][8]);
						$homepage .="\n";
						$homepage .="Farmaco: ".$csv[$i][4]."\n";
						$homepage .="Principio attivo: ".$csv[$i][0]."\n";
						$homepage .="Confezione di riferimento: ".$csv[$i][1]."\n";
						$homepage .="Confezione: ".$csv[$i][5]."\n";
						$homepage .="Prezzo al pubblico: ".$csv[$i][8]."€\n";
						$homepage .="Ditta: ".$csv[$i][6]."\n";
						$homepage .="Codice gruppo equivalenza: ".$csv[$i][11]."\n";
						$homepage .="____________\n";

				}

			}elseif(strpos($text,'h?') !== false){
				$text=str_replace("h?","",$text);
				if ($text==""){
							$location="Inserire almeno una parola";
							$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
							$telegram->sendMessage($content);
						}
				$location="Sto cercando i farmaci di Classe H con denominazione: ".$text;
			  $content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
				$telegram->sendMessage($content);
		   	$text=str_replace(" ","%20",$text);
				$inizio=2;
				$homepage ="";
				$text=strtoupper($text);
				  $urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20B%20LIKE%20%27%25";
				  $urlgd .=$text;
				  $urlgd .="%25%27&key=18EDVuGRwVckXvrxXp1RvTXuoNDyIEuwfi2rWaJEvcVA&gid=1722773992";
					sleep (1);
					$csv = array_map('str_getcsv',file($urlgd));
				//var_dump($csv[1][0]);
					$count = 0;
					foreach($csv as $data=>$csv1){
						$count = $count+1;
					}
					if ($count ==0 || $count ==1){
								$location="Nessun risultato trovato";
								$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
								$telegram->sendMessage($content);
							}

					for ($i=$inizio;$i<$count;$i++){
$csv[$i][3]=str_replace(".",",",$csv[$i][3]);
						$homepage .="\n";
						$homepage .="Principio attivo: ".$csv[$i][0]."\n";
						$homepage .="Descrizione: ".$csv[$i][1]."\n";
						$homepage .="Denominazione: ".$csv[$i][2]."\n";
						$homepage .="Prezzo al pubblico: ".$csv[$i][3]."€\n";
						$homepage .="Ditta: ".$csv[$i][6]."\n";
						$homepage .="Codice: ".$csv[$i][7]."\n";
						$homepage .="Codice gruppo equivalenza: ".$csv[$i][8]."\n";
						$homepage .="____________\n";

				}

			}elseif(strpos($text,'p?h') !== false){
					$text=str_replace("p?h","",$text);
					if ($text==""){
								$location="Inserire almeno una parola";
								$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
								$telegram->sendMessage($content);
							}
					$location="Sto cercando i farmaci di Classe H con principio attivo: ".$text;
				  $content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
					$telegram->sendMessage($content);
			   	$text=str_replace(" ","%20",$text);
					$inizio=2;
					$homepage ="";
				//	$text=strtoupper($text);
					  $urlgd  ="https://spreadsheets.google.com/tq?tqx=out:csv&tq=SELECT%20%2A%20WHERE%20A%20LIKE%20%27%25";
					  $urlgd .=$text;
					  $urlgd .="%25%27&key=18EDVuGRwVckXvrxXp1RvTXuoNDyIEuwfi2rWaJEvcVA&gid=1722773992";
						sleep (1);
						$csv = array_map('str_getcsv',file($urlgd));
					//var_dump($csv[1][0]);
						$count = 0;
						foreach($csv as $data=>$csv1){
							$count = $count+1;
						}
						if ($count ==0 || $count ==1){
									$location="Nessun risultato trovato";
									$content = array('chat_id' => $chat_id, 'text' => $location,'disable_web_page_preview'=>true);
									$telegram->sendMessage($content);
								}

						for ($i=$inizio;$i<$count;$i++){
							$csv[$i][3]=str_replace(".",",",$csv[$i][3]);
							$homepage .="\n";
							$homepage .="Principio attivo: ".$csv[$i][0]."\n";
							$homepage .="Descrizione: ".$csv[$i][1]."\n";
							$homepage .="Denominazione: ".$csv[$i][2]."\n";
							$homepage .="Prezzo al pubblico: ".$csv[$i][3]."€\n";
							$homepage .="Ditta: ".$csv[$i][6]."\n";
							$homepage .="Codice: ".$csv[$i][7]."\n";
							$homepage .="Codice gruppo equivalenza: ".$csv[$i][8]."\n";
							$homepage .="____________\n";

					}

				}


		$chunks = str_split($homepage, self::MAX_LENGTH);
		foreach($chunks as $chunk) {
			$content = array('chat_id' => $chat_id, 'text' => $chunk,'disable_web_page_preview'=>true);
			$telegram->sendMessage($content);

		}
		$this->create_keyboard_temp($telegram,$chat_id);
exit;
}

	}

	function create_keyboard_temp($telegram, $chat_id)
	 {
			 $option = array(["ClasseA","ClasseC","ClasseH"],["Informazioni"]);
			 $keyb = $telegram->buildKeyBoard($option, $onetime=false);
			 $content = array('chat_id' => $chat_id, 'reply_markup' => $keyb, 'text' => "[Digita a?, c? oppure h? a seconda della Classe di farmaco da ricercare]");
			 $telegram->sendMessage($content);
	 }


}

?>
