<?php

namespace Akane\Core;

class Cli
{
    public $version = '3.0.3-dev';
    public $command_list;

    public function __construct()
    {
        $this->command_list = array(
            array(
                'command' => '-h, --help',
                'command_desc' => 'Show this help message',
            ),
            array(
                'command' => '-V, --version',
                'command_desc' => 'Show version',
            ),
            array(
                'command' => '',
                'command_desc' => '',
            ),
            array(
                'command' => 'serve',
                'command_desc' => 'Run Akane built-in server with default port 8000',
            ),
            array(
                'command' => 'serve -p <port number>',
                'command_desc' => 'Run Akane built-in server with desired port number',
            ),
            array(
                'command' => '',
                'command_desc' => '',
            ),
            array(
                'command' => 'mig',
                'command_desc' => 'Show all migration available commands',
            ),
            array(
                'command' => 'mig create [CamelCaseClassName]',
                'command_desc' => 'Create new migration',
            ),
            array(
                'command' => 'mig status',
                'command_desc' => 'Show migration status',
            ),
            array(
                'command' => 'mig migrate',
                'command_desc' => 'Run migration up',
            ),
            array(
                'command' => 'mig rollback',
                'command_desc' => 'Run migration down (revert)',
            ),
            array(
                'command' => 'mig seed:run',
                'command_desc' => 'Run all seeder',
            ),
            array(
                'command' => 'mig seed:run -S [SeederClassname]',
                'command_desc' => 'Run specific seeder classname',
            ),
        );
    }

    public function cliHeader()
    {
        return "\n".str_repeat('#', 100)."\033[1;34m
    ___    __                   _____
   /   |  / /______ _____  ___ |__  /
  / /| | / //_/ __ `/ __ \/ _ \ /_ < 
 / ___ |/ ,< / /_/ / / / /  __/__/ / 
/_/  |_/_/|_|\__,_/_/ /_/\___/____/  

\033[0m\033[0;32mAkane Framework\033[0m version \033[1;33m".$this->version."\033[0m\n".str_repeat('_', 100)."\n\n";

    }

    public function cliFooter()
    {
        return "\n\n".str_repeat('~', 100)."\n\n";
    }

    public function showVersion()
    {
        echo $this->cliHeader();
        echo "version ".$this->version."\n\nAuthor: WebHade Creative\nLast updated: 04 Sep 2019";
        echo $this->cliFooter();
    }

    public function showHelp()
    {
		$usage = "\033[1;33mUsage:\033[0m\nphp akane [command]\n\n";
		
		$command_head = "\033[1;33mAvailable commands:\033[0m\n\n";
		
        $command_list = "";
        $cmnd = array();
        
        foreach ($this->command_list as $cmds) {
            $cmnd[] = strlen($cmds['command']);
        }
        
        $max_length = max($cmnd) + 3;

        foreach ($this->command_list as $cmd) {
            $cmd['command'] = str_pad($cmd['command'], $max_length, ' ');
            $command_list .= "\033[0;32m".$cmd['command']."\033[0m".$cmd['command_desc']."\n";
        }

		$command = $command_head.$command_list;

        echo $this->cliHeader();
		echo $usage.$command;
		echo $this->cliFooter();
    }
    
    public function serve($port = '8000')
    {
        $host = 'localhost';

        if ($port==''){
            $port = '8000';
        }

        echo "\033[0;32mAkane server started on http://{$host}:{$port}/\033[0m\n";

        passthru(PHP_BINARY . " -S {$host}:{$port} -t public_html public_html/app.php 2>&1");
    }

    public function migrationTools()
    {
        $phinx = new \Phinx\Console\PhinxApplication('- For Akane Framework v3');
        $phinx->run();
    }
}
