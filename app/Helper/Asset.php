<?php

namespace Akane\Helper;

class Asset
{
	public function js($filename, $async = false)
    {
        $f = __DIR__ . '/../..' . $this->getPath('js').$filename;

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

    public function css($filename, $is_file = true, $media = 'screen')
    {
        $f = __DIR__ . '/../..' . $this->getPath('css').$filename;

        $filemtime = '';
        if (file_exists($f)){
            $filemtime = filemtime($f);
            return '<link rel="stylesheet" href="'.$this->getPath('css').$filename.($is_file ? '?'.$filemtime : '').'" media="'.$media.'">';
        }
    }

    public function extcss($cssurl, $media = 'screen')
    {
        return '<link rel="stylesheet" href="'.$cssurl.'" media="'.$media.'">';
    }

    public function customJs($javascript, $async = false)
    {
        return '<script '.($async ? 'async' : '').' type="text/javascript">'.$javascript.'</script>';
    }

    public function getPath($path)
    {
        return implode('/', array('','assets', $path, ''));
    }
}