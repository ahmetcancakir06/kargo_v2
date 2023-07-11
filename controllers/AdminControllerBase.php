<?php

defined('BASEPATH') or exit('No direct script access allowed');

class AdminControllerBase extends AdminController
{

    public function __construct()
    {
        parent::__construct();
        //$this->load->library(KARGO_MODULE_NAME . '/' . 'module_settings');

    }

    function connectDb($ci, $credential, $prefix = 'tbl')
    {

        $config['hostname'] = $credential['sql_host'] . ':' . $credential['sql_port'];
        $config['username'] = $credential['sql_username'];
        $config['password'] = $credential['sql_password'];
        $config['database'] = $credential['sql_database'];
        $config['dbdriver'] = "mysqli";
        $config['dbprefix'] = $prefix;
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_turkish_ci";

        $DB2 = $ci->load->database($config, true);
        return $DB2;
    }
    
    public function access_denied_ajax()
    {
        $data = [
            "error" => _l('access_denied')
        ];
        echo json_encode($data);
        die;
    }

    public function respErrorAjax($msg, $ex = null)
    {
        $data = [
            "error" => _l($msg)
        ];
        if ($ex != null) {
            $data["ex"] = $ex;
        }
        echo json_encode($data);
        die;
    }

    public function respSuccesAjax($msg, $ex = null)
    {
        $data = [
            "ok" => _l($msg)
        ];
        if ($ex != null) {
            $data["ex"] = $ex;
        }
        echo json_encode($data);
        die;
    }
    public function repDoubleAnswer($errors, $succes)
    {
        $data = [];
        $errors_message = explode(",", $errors);
        $ersay = 0;

        foreach ($errors_message as $mes) {
            if (!empty($mes)) {

                array_push($data, ['errors' => $mes]);
                $ersay += 1;
            }
        }

        $sucsay = 0;
        $succes_message = explode(",", $succes);
        foreach ($succes_message as $mes) {
            if (!empty($mes)) {

                array_push($data, ['succes' => $mes]);
                $sucsay += 1;
            }
        }

        echo json_encode($data);
        die;
    }
    public function flsuhJson($data)
    {
        header('Content-Type: application/json', true);
        echo (json_encode($data));

        // This is what you need
        ob_flush();
        flush();
    }

    public function ajax_report($filename = '')
    {
        if (!has_permission('system_care', '', 'care_functions')) {
            $this->access_denied_ajax();
        }
        if ($filename == '') $filename = dirname(__FILE__) . '/report.json';
        $myfile = fopen($filename, "r");
        $content = fread($myfile, filesize($filename));
        fclose($myfile);
        echo $content;
        die;
    }

    public function testUrl($Url = '')
    {
        try {
            $agent = "Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.114 Safari/537.36";
            $opts = array(
                'http' => array(
                    'method' => "GET",
                    'header' => "User-Agent: " . $agent . "\r\n"
                )
            );

            $context = stream_context_create($opts);
            $file_headers = @get_headers($Url, false, $context);

            if (!$file_headers || $file_headers[0] != 'HTTP/1.1 200 OK') {
                return false;
            } else {
                return true;
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function postUrlYoutube($url = '')
    {
        /*set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
            throw new ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
        }, E_WARNING);
        try {
            $curljson = json_decode(file_get_contents($url));

            return $curljson;
        } catch (Exception $e) {
            return $e;
        }*/
        $method = "GET";
        $opts = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peername' => false,
                // Instead ideally use
                // 'cafile' => 'path Certificate Authority file on local filesystem'
            ),
            'http' => array(
                'method' => $method,
                'header'  => "Content-Type: application/x-www-form-urlencoded",
            )
        );
        set_error_handler(function ($err_severity, $err_msg, $err_file, $err_line, array $err_context) {
            throw new ErrorException($err_msg, 0, $err_severity, $err_file, $err_line);
        }, E_WARNING);
        try {
            $context = stream_context_create($opts);
            $result = file_get_contents($url, false, $context);
            
            return json_decode($result);
        } catch (Exception $e) {
            return $e;
        }
    }
    public function postServer($url, $method = 'POST', $data = null)
    {
        
        $opts = null;
        if ($data != null) {
            $postdata = http_build_query(
                $data
            );

            $opts = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peername' => false,
                    // Instead ideally use
                    // 'cafile' => 'path Certificate Authority file on local filesystem'
                ),
                'http' => array(
                    'method' => $method,
                    'header'  => "Content-Type: application/x-www-form-urlencoded",
                    'content' => $postdata,
                )
            );
        } 
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}
