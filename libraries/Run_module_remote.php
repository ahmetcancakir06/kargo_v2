<?php
set_include_path($path=__DIR__ . '/vendor2/phpseclib');
include (__DIR__ . '/vendor2/autoload.php');
$loader = new \Composer\Autoload\ClassLoader();
use phpseclib3\Net\SSH2;
use phpseclib3\Crypt\PublicKeyLoader;

class Run_module_remote
{
    private $ci = null;
    private $serverInfo = [];
    private $path = '';
    private $ssh = null;
    private $key = null;

    public function __construct()
    {
        $this->ci = &get_instance();
        //$this->ci->load->model(SERVER_API_MODULE_NAME.'/system_jobs_model');
        //$this->ci->load->model(SERVER_API_MODULE_NAME.'/system_jobs_logs_model');
    }

    private static function getPath(){
        $reflector = new \ReflectionClass(self::class);
        return dirname($reflector->getFileName());
    }

    private function generateRandomString($length = 10) {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function runCommand($command, $asSudo = false){
        $fullCommand = $command;
        if ($asSudo) {
            $fullCommand = 'sudo '.$fullCommand;
        }
        // if ($this->moduleInfo["print_run_command"] == '1') {
        //     $this->addLog('runCommand : '. $fullCommand);
        // }
        try {
$output = "";
$result = exec("whoami", $output);
if ($result !== false){
$sshPath = "/home/".$output[0].'/.ssh';
} else {
return "whoami komutu çalıştıtılamadı! hata:".var_export($output,true);
}
$key = file_get_contents($sshPath.'/id_rsa_server');


        } catch (\Exception $ex) {
            return [false, 'Hata(1): '.$ex->getMessage()];
        }
        try {
            $this->key = PublicKeyLoader::load($key,SERVER_API_MODULE_KEY_PASS);
        } catch (\Exception $ex) {
            return [false, 'Hata(2): '.$ex->getMessage()];
        }
        $this->ssh = new SSH2($this->serverInfo['hostname_ip'],$this->serverInfo['ssh_port']);
        if (!$this->ssh->login($this->serverInfo['remote_user'], $this->key)) {
            return [false, "login failed"];
        }
        try {
            $data = $this->ssh->exec($fullCommand);
            return [true, $data];
        } catch (\Exception $ex) {
            return [false, 'Hata(3): '.$ex->getMessage()];
        }
    }

    public function setModule($serverInfo=[]) {
        $this->serverInfo = $serverInfo;
        $this->path = $this->serverInfo['scripts_fullpath'] . DIRECTORY_SEPARATOR;
     }

    public function destroy(){
        if ($this->ssh != null && $this->ssh->isConnected()) {
            $this->ssh->disconnect();
        }
    }
}
