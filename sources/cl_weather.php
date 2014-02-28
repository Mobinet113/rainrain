<?php
if(isset($_POST['AjaxAction'])){
	$a = $_POST['AjaxAction'];
	$obj = new weather;
	if($a == 'getLocs'){
		$obj->locations($_POST['data']);
	}
}

class weather {
	private $xmlURL = 'http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/xml/';	
	private $apiKey = '<API KEY>';
	public $locID;
	
	private static function download_page($path){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$path);
		curl_setopt($ch, CURLOPT_FAILONERROR,1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		$retValue = curl_exec($ch);			 
		curl_close($ch);
		return $retValue;
	}
	
	private static function saveFile($dir, $dat){
		$dir = fopen($dir, "w+");
		if(fwrite($dir, $dat)){
			return true;
		} else {
			return false;
		}
	}

	private function getDat(){
		$sXML = weather::download_page($this->xmlURL . $this->locID . '?res=daily&key=' .$this->apiKey);
		return new SimpleXMLElement($sXML);
	}
	
	private function drawImg($val){
		$val = $val->Rep['W'];
		if($val < 7){false;}
		elseif($val == 7 || $val == 8){ $val = 'cloud';}
		elseif($val >= 9 || $val <= 12){ $val = 'drizzle';}
		elseif($val >= 13 || $val <= 15){ $val = 'rain';}
		elseif($val >= 16 || $val <= 18){ $val = 'sleet';}
		elseif($val >= 19 || $val <= 21){ $val = 'hail';}
		elseif($val >= 22 || $val <= 27){ $val = 'snow';}
		elseif($val >= 28 || $val <= 29){ $val = 'thunder';}
		
		if(date('G', time()) >= 16){
			if($val == 1){$val = 'moon';}
			elseif($val == 7){$val = 'mooncloud';}
			elseif($val == 'cloud'){$val = 'mooncloud';}
		}
		
		echo '<div class="weather_ico"><img src="media/ico/weather/_'.$val.'.png" /></div><br />';
	}

	public function printDat($start = null){
		$i = 0;
		
		echo '<div id="spread">'.$this->getDat()->DV->Location['name'].'</div>';

		try{
			if(!empty($this->getDat()->DV->Location->Period)){
				foreach($this->getDat()->DV->Location->Period as $k => $v){
					$i++;
					if($i == 1){$date = 'Today';}
					elseif($i == 2){$date = 'Tomorrow';}
					else{
						$date = new DateTime(rtrim($v['value'], "Z"));
						$date = $date->format('l');
					}
					echo '
						<div class="vBlock"id="col'.$i.'" style="top:-'.$start.';">
							<div class="wrapper">';
					echo '		<h3>'.$date.'</h3>';
								$this->drawImg($v);
								
								foreach($v->Rep as $k2 => $v2){						
					echo '				<div class="tempGroup">';
											if($v2 == 'Day'){echo '<div class="temp"><span class="nums">&#9651; '.$v2['Dm'].'</span><img src="media/ico/weather/c.png" ></div>';}
											if($v2 == 'Night'){echo '<div class="temp"><span class="nums">&#9661; '.$v2['Nm'].'</span><img src="media/ico/weather/c.png" ></div>';}
					echo'				</div>';					
								}
					echo '	
								<div class="wind"><span>'.$v->Rep['S'].'</span><div class="windBG '.$v->Rep['D'].'"></div></div>
					';
					echo '	</div>
						</div>';
				}
			} else {
				echo '<div id="error">Sorry, we could not find any information on this location at this time</div>';
				$this -> locID = 3672;
				$this -> printDat();
			}
		} catch(Exception $e){
			echo '<div id="error">Sorry, we could not find any information on this location</div>';
		}
	}	
	
	public static function nightTime($time){
		echo (date('G', time()) >= $time ? '<div id="night"></div>' : ''); 
	}
	
	private function getLocDat(){
		$sXML = weather::download_page('http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/datatype/sitelist?key=' .$this->apiKey);
		$XML = new SimpleXMLElement($sXML);
		return $XML;
	}
	
	public function areas(){
		$XML = $this->getLocDat();
	
		foreach($XML->Location as $k => $v){
			if(!empty($v['unitaryAuthArea'])){
				$area[] = $v['unitaryAuthArea'];
			}
		}	
		$area = array_unique($area);
		sort($area, SORT_STRING);
			for($i = 0; $i < count($area); $i++){
				echo '<option onclick="" value="'.$area[$i].'">'.$area[$i].'</option>';
			}
	}
	
	public function locations($area){
		$XML = $this->getLocDat();
			
		for($i = 0; $i < count($area); $i++){
			$search = $XML->xpath("//Location[@unitaryAuthArea='$area']"); //XPath Search
			foreach($search as $v){
				echo '<option value="'.$v['id'].'">'.$v['name'].'</option>';
			}
		}
	}
	
}



