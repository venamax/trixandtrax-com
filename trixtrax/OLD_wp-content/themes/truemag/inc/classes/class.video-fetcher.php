<?php
if(!class_exists('Video_Fetcher')){
/**
 * Added since TrueMag 1.0
 * Author admin@cactusthemes.com
 *
 * This class helps to fetch video information from popular video network such as YouTube and Vimeo
 */
	class Video_Fetcher{
		/* Extract Video ID from URL */
		public static function extractIDFromURL($url){
			$channel = Video_Fetcher::extractChanneldFromURL($url);
			$id = '';
			
			switch($channel){
				 case 'youtube':
					$id = substr($url, strrpos($url,'v=')+2);
					break;
				case 'vimeo':
					$id = substr($url, strrpos($url,'/')+1);
					break;
				default:
					$id = '';
			}
			
			return $id;
		}
		
		/* Extract Channeld Name from URL */
		public static function extractChanneldFromURL($url){
			if(strpos($url,'youtube.com') !== false){
				return 'youtube';
			} else if(strpos($url,'vimeo.com') !== false){
				return 'vimeo';
			} else return '';
		}
		
		/* Fetch Video Metadata from URL 
		 *
		 * $url: Video URL
		 *
		 * return array() of field-values
		 * 			array("title"=>"","description"=>"","duration"=>"","tags"=>"");
		 */
		public static function fetchData($url,$fields = array()){
			$id = Video_Fetcher::extractIDFromURL($url);
			$channel = Video_Fetcher::extractChanneldFromURL($url);
			
			switch($channel){
				case 'youtube': return Video_Fetcher::fetchYoutubeData($id, $fields);
				case 'vimeo': return Video_Fetcher::fetchVimeoData($id, $fields);
				default: return null;
			}
		}
		
		/* Fetch Youtube Video Metadata 
		 *
		 * require extension=php_openssl.dll in php.ini
		 */
		private static function fetchYoutubeData($video_id){
			$array = array('title'=>'','description'=>'','duration'=>'','tags'=>'','viewCount'=>'');
			
			$xml = @file_get_contents('https://gdata.youtube.com/feeds/api/videos/'.$video_id.'?v=2');
			
			if($xml !== false && $xml != ''){
				/* cannot parse XML using simplexml_load_string or DOMDocument because of child tags like "media:description"
				 * so we use preg_match to search
				 */
				preg_match('/<title[^>]*?>(.*?)<\/title>/s', $xml, $title);
				preg_match('/<media:description[^>]*?>(.*?)<\/media:description>/s', $xml, $desc);
				preg_match('/<yt:duration seconds=[^"](.*?)[^"]\/>/s', $xml, $duration);
				preg_match('/viewCount=[^"](.*?)[^"]\/>/s', $xml, $views);
				
				$array['title'] = $title[1];
				$array['description'] = $desc[1];
				$array['duration'] = $duration[1];
				$array['tags'] = '';
				$array['viewCount'] = $views[1];
				
			}
			
			return $array;
		}
		
		/* Fetch Vimeo Video Metadata */
		private static function fetchVimeoData($video_id){
			$array = array('title'=>'','description'=>'','duration'=>'','tags'=>'','viewCount'=>'');
			
			$xml = @file_get_contents('http://vimeo.com/api/v2/video/'.$video_id.'.xml');
						
			if($xml !== false && $xml != $video_id . ' is not a valid method.'){			
				$xml = simplexml_load_string($xml);								
				$vimeo = $xml->video[0];				
				$array['title'] = $vimeo->title[0]->__toString();
				$array['description'] = $vimeo->description[0]->__toString();
				$array['duration'] = $vimeo->duration[0]->__toString();
				$array['tags'] = $vimeo->tags[0]->__toString();
				if(isset($vimeo->stats_number_of_plays)){
				$array['viewCount'] = $vimeo->stats_number_of_plays[0]->__toString();
				}
			}
			
			return $array;
		}
	}
}