<?php

namespace Akane\Core;

class Layout extends \Akane\Core\Base
{
    private $hooks = array();

    public function render($template_name, array $template_args = array())
    {
        extract($this->hooks);

        $filename = $this->getTemplateFile($template_name);
        if (file_exists($filename)){
            $container = $this->getContainer();
            $akane = $container->getAll();
            extract($template_args);
            ob_start();
            include $filename;
            return ob_get_clean();
        } else {
            return 'Internal Error: No Template for <b>'.$template_name.'</b> on path: '.$filename;
        }
    }

    public function getTemplateFile($template)
    {
        $pattern = '/(.*)\:/';

        if (preg_match($pattern, $template)==true)
        {
            preg_match_all($pattern, $template, $result);

            if (isset($result[1][0])){
                $plugin = $result[1][0];
            }
            
            $template = str_replace($plugin.':', '', $template);

            $appview = implode(DIRECTORY_SEPARATOR, array(__DIR__,'..','..','..','..','..','app', 'Plugins', $plugin, 'Template', $template.'.php'));
            $coreview = implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'Plugins', $plugin, 'Template', $template.'.php'));

        } else {
            $appview = implode(DIRECTORY_SEPARATOR, array(__DIR__,'..','..','..','..','..','app', 'Template', $template.'.php'));
            $coreview = implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'Template', $template.'.php'));
        }

        if (file_exists($appview)){
            return $appview;
        } else {
            return $coreview;
        }
    }
    
    public function renderJson($output=array())
    {
        header('Content-type: application/json');
        echo json_encode($output);
    }

    public function setHook($name, $value)
    {
        $this->hooks[$name] = $value;
    }
}