<?php
/**
 * Package to manage strings. 
 * Used FileName to manage files strings like myfile.myextension. 
 * It's not able to contain directory parameters like /myfolder/myfile.ext
 * @package    Stringtools
 * @author     Jérôme <ilune.fr>
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version 1.0
 * url https://github.com/ilune-fr/Stringtools
 */

namespace Ilune\Stringtools;
use Ilune\Stringtools\String;

class FileName extends String {
    
    /**
     * remove special chars in file name (keep extension )
     * @param type $exception
     * @param type $default
     * @return \Ilune\Stringtools\FileName
     */
    function removeSpecialChars($exception='_\- ',$default=' ') {
        if (strlen($this->string)==0) {
            return $this;
        }
        $rpos = strrpos($this->string, '.');
        $base = substr($this->string,0,$rpos);
        $base = new String($base);
        $base->removeSpecialChars($exception,$default);
        $this->string = $base.substr($this->string,$rpos);
        return $this;
    }
    
    /**
     * add numerotation to file if exist in given directory
     * @param string $dir
     * @return \Ilune\Stringtools\FileName
     */
    function uniqueToDir($dir) {
        if (file_exists($dir.$this->string)) {
            $cur = $rpos = strrpos($this->string, '.');
            $cur --;
            $num = false;
            while ($cur != 0 && is_numeric(substr($this->string,$cur,($rpos-$cur))) ) {
                $num = substr($this->string,$cur,($rpos-$cur));
                $length = $rpos-$cur;
                $cur --;
            }
            if (!$num) {
                //some numeric found
                $num = 2;
            } else {
                $num++;
                if (strlen($num) !== $length) {
                    $num = str_pad($num,$length,'0',STR_PAD_LEFT);
                }
            }
            $this->string = substr($this->string,0,$cur+1).$num.substr($this->string,$rpos);
            $this->uniqueToDir($dir);


        } 
        return $this;
    }

    /*
     * remove current file extension (remove dot too)
     * @return \Ilune\Stringtools\FileName
     */
    function removeExtension() {
        $pos = strrpos($this->string,'.');
        if ($pos !==false && $pos > 0) {
            $this->string = substr($this->string,0,$pos);
        }
        return $this;
    }
    /**
     * replace file extension by the one passed in parameter
     * @param string $newExtension
     * @return \Ilune\Stringtools\FileName
     */
    function replaceExtension($newExtension) {
        $pos = strrpos($this->string,'.');
        if ($pos !==false && $pos > 0) {
            $this->string = substr($this->string,0,$pos).'.'.$newExtension;
        } else {
            $this->string = $this->string .'.'.$newExtension;
        }
        return $this;
    }

    /**
     * create file in a dir
     * @param string $dir
     * @return \Ilune\Stringtools\FileName
     */
    function touch($dir) {
        if (strpos($this->string,'/') !== false) {
            throw new Exception('FileName.php: canno\'t touch file with "/" in it\'s name:'.$this->string);
        }
        touch($dir.$this->string);
        return $this;
    }

}
