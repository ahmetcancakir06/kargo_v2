<?php

/** @package  API Request */
class Api_Request
{
    public $username="";
    public $password="";
    public $api_url = '';
    private $ci = null;

    public function __construct()
    {
        $this->ci = &get_instance();
        $this->api_url = '';
        $this->username = '';
        $this->password = '';

    }

    public function callAPI($data = null, $method = 'GET')
    {
        
        $username = $this->username;
        $password = $this->password;
        if(empty($username) || empty($password)){
            echo "Giriş için email ve şifre giriniz";
            die;
        }
        $url = $_SERVER['HTTP_ORIGIN']."/admin/authentication";
        //$url = "http://ahmet.incsinnovations.com/admin/authentication";
        $cookie_link=$_SERVER['DOCUMENT_ROOT']."/modules/server_api/docs/";

        $postinfo = "email=" . $username . "&password=" . $password;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_POST, 0);
        $getcookie=curl_exec($ch);

        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $getcookie, $matches);

        $cookies = array();
        foreach ($matches[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }
        $csrf_cookie_name_token="csrf_cookie_name=".$cookies['csrf_cookie_name'];
        $sp_session_token="sp_session=".$cookies['sp_session'];
        $cookiet=$csrf_cookie_name_token."; ".$sp_session_token;
        curl_setopt($ch, CURLOPT_COOKIE,$cookiet);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
        $post = [
            'csrf_token_name' => $csrf_cookie_name_token,
            'email' => urlencode($this->username),
            'password'   => urlencode($this->password),
        ];
        $csrfEx=explode("=",$csrf_cookie_name_token);
        $csrfpost="csrf_token_name=".$csrfEx[1];
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt(
            $ch,
            CURLOPT_POSTFIELDS,
            $csrfpost.'&email='.urlencode($this->username).'&password='.urlencode($this->password)
        );

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POST, 1);
        $login=curl_exec($ch);

        $query_str = '?';
        foreach ($data as $key => $value) {
            $query_str .= $key . "=" . rawurlencode($value) . "&";
        }
        //page with the content I want to grab
        curl_setopt($ch, CURLOPT_URL, $this->api_url . substr($query_str, 0, strlen($query_str) - 1));
        curl_setopt($ch, CURLOPT_HEADER, false);

        //do stuff with the info with DomDocument() etc
        $html = curl_exec($ch);
        print_r($html);
        curl_close($ch);
        
    }
}
