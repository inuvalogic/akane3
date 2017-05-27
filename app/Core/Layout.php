<?php

namespace Akane\Core;

class Layout extends \Akane\Core\Base
{
	public function render($template_name, array $template_args = array())
    {

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
        return implode(DIRECTORY_SEPARATOR, array(__DIR__, '..', 'Template', $template.'.php'));
    }
    
    public function renderJson($output=array())
    {
        header('Content-type: application/json');
        echo json_encode($output);
    }
}