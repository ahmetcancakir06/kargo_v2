<?php

class Update_module
{
    //private $ci;

    public function __construct()
    {
        //$this->ci = &get_instance();
        //$this->ci->load->model('system_care/system_care_scripts_model');
    }

    public function update(){
        $moduleDirPath = module_dir_path(SERVER_API_MODULE_NAME);
        chdir($moduleDirPath);
        $output = ' >> "' .$moduleDirPath.'controllers/update_report.txt" 2>&1';
        if (file_exists($moduleDirPath.'controllers/update_report.txt')){
            file_put_contents($moduleDirPath.'controllers/update_report.txt','');
        }
        if (file_exists($moduleDirPath.'.git.zip')) {
            if(file_exists($moduleDirPath.'.git')) {
                $runCommand = '/bin/rm -rf '.$moduleDirPath.'.git';
                file_put_contents($moduleDirPath.'controllers/update_report.txt',$runCommand.PHP_EOL, FILE_APPEND);
                exec($runCommand.$output);
            }
            $runCommand = '/usr/bin/unzip -q -o '.$moduleDirPath.'.git.zip'.' -d '.$moduleDirPath;
            file_put_contents($moduleDirPath.'controllers/update_report.txt',$runCommand.PHP_EOL, FILE_APPEND);
            exec($runCommand.$output);

            $runCommand = '/bin/rm -rf '.$moduleDirPath.'.git.zip';
            file_put_contents($moduleDirPath.'controllers/update_report.txt',$runCommand.PHP_EOL, FILE_APPEND);
            exec($runCommand.$output);
        }
        $runCommand = '/usr/bin/git fetch --all';
        file_put_contents($moduleDirPath.'controllers/update_report.txt',$runCommand.PHP_EOL, FILE_APPEND);
        exec($runCommand.$output);

        $runCommand = '/usr/bin/git reset --hard origin/master';
        file_put_contents($moduleDirPath.'controllers/update_report.txt',$runCommand.PHP_EOL, FILE_APPEND);
        exec($runCommand.$output);
        return '';
    }
}