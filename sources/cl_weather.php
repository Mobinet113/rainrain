<?php
class weather {
	private $xmlURL = 'http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/xml/';	
	private $apiKey = 'API KEY';
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
		foreach($this->getDat()->DV->Location->Period as $k => $v){
			$i++;
		echo '
			<div class="vBlock"id="col'.$i.'" style="top:-'.$start.';">
				<div class="wrapper">';
		echo '		<h3>'.rtrim($v['value'], "Z").'</h3>';
					$this->drawImg($v);
					
					foreach($v->Rep as $k2 => $v2){
						if($v2 != 'Night'){			
		echo '				<span class="nums">&#9651; '.$v2['Dm'].'</span><img src="media/ico/weather/c.png" ><br />
							<span class="nums">&#9661; '.$v2['FDm'].'</span><img src="media/ico/weather/c.png" ><br />';
						}
					}
		echo '	</div>
			</div>';
		}
	}	
	
	public static function nightTime($time){
		echo (date('G', time()) >= $time ? '<div id="night"></div>' : ''); 
	}
	
	public function locations(){
		$sXML = weather::download_page('http://datapoint.metoffice.gov.uk/public/data/val/wxfcs/all/datatype/sitelist?key=' .$this->apiKey);
		return new SimpleXMLElement($sXML);
	}
}



