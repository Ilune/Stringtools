<?php
/**
 * Package to manage strings. 
 * 
 * @package    Stringtools
 * @author     Jérôme <ilune.fr>
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version 1.0
 * url https://github.com/ilune-fr/Stringtools
 */

namespace Ilune\Stringtools;

class String {
	protected $string;
        /**
         * @param string $string
         * @throws Exception
         */
	function __construct($string) 
	{
		if (!is_string($string)) {
			throw new \Exception('Bad type '.  gettype($string));
		}
		$this->string = $string;
                return $this;
	}

        /**
         * decode html entities
         * @return \Ilune\Stringtools\String
         */
	function htmlEntityDecode() 
	{
		$this->string = html_entity_decode($this->string,ENT_NOQUOTES,'UTF-8');
		return $this;
	}
        /**
         * decode html entities
         * @return \Ilune\Stringtools\String
         */
	function htmlEntityEncode() 
	{
		$this->string = htmlentities($this->string,'UTF-8',ENT_NOQUOTES);
		return $this;
	}

        /**
         * 
         * @return \Ilune\Stringtools\String
         */
        function removeStyleAttribute() 
        {
            $this->string = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $this->string);
            return $this;
        }
        /**
         * remove tags
         * @return \Ilune\Stringtools\String
         */
	function stripTags() 
	{
		$this->string = strip_tags($this->string );
		return $this;
	}

        /**
         * lower case
         * @return \Ilune\Stringtools\String
         */
	function toLower() 
	{
		$this->string = strtolower($this->string );
		return $this;
	}
        
        /**
         * upper case
         * @return \Ilune\Stringtools\String
         */
	function toUpper() 
	{
		$this->string = strtoupper($this->string );
		return $this;
	}

        /**
         * 
         * @param string $exception caracters not to remove
         * @param string $default the caracter that replace not wanted caracters
         * @return \Ilune\Stringtools\String
         */
	function removeSpecialChars($exception='_\- ',$default=' ')
	{
		$this->string  = strip_tags($this->string );
		$search = array ('@é|è|ê|ë|É|È|Ê|Ë@i','@à|â|ä|Â|Ä@i','@î|ï|Î|Ï@i','@û|ù|ü|Û|Ü@i','@ô|ö|Ô|Ö@i','@ç@i','@[^a-zA-Z0-9'.$exception.']@');
		$replace = array ('e','a','i','u','o','c',$default);
		$this->string  = preg_replace($search, $replace, $this->string );
		return $this;
	}

        /**
         * keep string until the last position of the given character
         * @param string $char
         * @return \Ilune\Stringtools\String
         */
        function keepUntilLastChar($char) {
            $rpos = strrpos($this->string, $char);
            $this->string = substr($this->string,$rpos);
            return $this;
        }

        /**
         * 
         * @param array $stopwordsContent
         * @return \Ilune\Stringtools\String
         * @throws \Exception
         */
	function removeStopWords($stopwordsContent)
	{
		$clearData = '';
		$total = count($stopwordsContent);
		if ($total ==0) {
			throw new \Exception('no stop words');
		}
		for ($i = 0; $i < $total; $i++) {
			$stopwordsContent[$i] = trim(strtolower($stopwordsContent[$i]));
		}
		//make array of search terms
		$wordsList = preg_split("/[\s,\'\.\"\t\n\f\r]+/", $this->string );
		foreach ($wordsList as $key => $line) {
			if (strlen($line) > 1) {
				if (in_array(strtolower(trim($line)), $stopwordsContent)) {
					$removeKey = array_search($line, $stopwordsContent);
					unset($wordsList[$removeKey]);
				} else {
					$clearData .= ' '.$line;
				}
			} else {
				unset($wordsList[$key]);
			}
		}
		$this->string  =$clearData;
		return $this;
	}
        
        /**
         * @return string
         */
	function __toString () 
	{
		return $this->string;
	}
        
        /**
         * @return \Ilune\Stringtools\String
         */
	function removeDuplicateWords() 
	{
		$this->string = implode(' ',array_unique(explode(' ',$this->string)));
		return $this;
	}
        
        /**
         * @return \Ilune\Stringtools\String
         */
	function removePunctuation()
	{
		$this->string = preg_replace('@\'|’|,|/|:|\.|;|-|\(|\)|#|«|»|\[|\]@i',' ',$this->string);
		return $this;
	}
        
        /**
         * just concat a string to current
         * @param string $string
         * @return \Ilune\Stringtools\String
         */
        function concat($string) 
        {
            $this->string = $this->string.$string;
            return $this;
        }
        
        /**
         * just check if the string is all uppercase
         * @return boolean
         */
        function checkIfUpperCase() {
            return (strtoupper($this->string) === $this->string) ;
        }
    function cleanup()
    {
        $this->string = preg_replace('@[^a-zA-Z0-9_\-\ éèêëÉÈÊËàâäÂÀÄîïÎÏûùüÛÜô…ö\&°Ô%ÖçÇñŒœ\@\.,\!+\?\=\'\"\(\)\:\;®’\/»«\[\]]@u','',$this->string);
        return $this;
    }

}

