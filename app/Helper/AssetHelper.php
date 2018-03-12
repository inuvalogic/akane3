<?php

namespace Akane\Helper;

class AssetHelper
{
	public static $footer_js = '';
    public static $header_css = array();

    public function js($filename, $async = false)
    {
        $f = '.' . $this->getPath('js').$filename;

        $filemtime = '';
        if (file_exists($f)){
            $filemtime = filemtime($f);
            return '<script '.($async ? 'async' : '').' type="text/javascript" src="'.$this->getPath('js').$filename.'?'.$filemtime.'"></script>';
        }
    }

    public function extjs($jsurl, $async = false)
    {
        return '<script '.($async ? 'async' : '').' type="text/javascript" src="'.$jsurl.'"></script>';
    }

    public function inlinejs($javascript, $async = false)
    {
        return '<script '.($async ? 'async' : '').' type="text/javascript">'.$javascript.'</script>';
    }

    public function css($filename, $media = 'screen')
    {
        $f = '.' . $this->getPath('css').$filename;

        $filemtime = '';
        if (file_exists($f)){
            $filemtime = filemtime($f);
            return '<link rel="stylesheet" href="'.$this->getPath('css').$filename.'?'.$filemtime.'" media="'.$media.'">';
        }
    }

    public function extcss($cssurl, $media = 'screen')
    {
        return '<link rel="stylesheet" href="'.$cssurl.'" media="'.$media.'">';
    }

    public function inlinecss($css, $media = 'screen')
    {
        return '<style media="'.$media.'" type="text/css">'.$css.'</style>';
    }

    public function get_css_content($filename)
    {
        $css = '';
        $f = '.' . $this->getPath('css').$filename;
        if (file_exists($f)){
            $css = file_get_contents($f);
        }
        return $css;
    }

    public function getPath($path)
    {
        return implode('/', array('','assets', $path, ''));
    }

    public function set_footer_js($js)
    {
        self::$footer_js .= $js;
    }

    public function show_footer_js()
    {
        return $this->inlinejs(self::$footer_js);
    }

    public function set_header_css($array)
    {
        if (is_array($array))
        {
            self::$header_css = array_merge(self::$header_css, $array);
        } else {
            throw new \Exception("Invalid Set Header CSS data type. Must be an array");
        }
    }

    public function load_header_css()
    {
        if (is_array(self::$header_css))
        {
            $loaded_css = '';
            foreach (self::$header_css as $filename)
            {
                $f = '.' . $this->getPath('css').$filename;
                if (file_exists($f)) {
                    $loaded_css .= $this->css($filename);
                }
                else if (parse_url($filename, PHP_URL_SCHEME)=='http') {
                    $loaded_css .= $this->extcss($filename);
                }
            }
            echo $loaded_css;
        }
    }
}