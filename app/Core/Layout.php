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
            return 'Internal Error: No Template for <b>'.$template_name.'</b>';
        }
    }

    public function getTemplateFile($template)
    {
        $appview = implode(DIRECTORY_SEPARATOR, array('app', 'Template', $template.'.php'));
        $coreview = implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'Template', $template.'.php'));

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