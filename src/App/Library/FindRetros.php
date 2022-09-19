<?php
namespace Cosmic\App\Library;

use Cosmic\App\Config;
use Cosmic\App\Models\Core;

class FindRetros {
    private $pageName, $callTimeout, $usingCloudFlare, $apiPath;
    function __construct() {
        $core = new Core();
        $this->pageName        = $core->settings()->findretros_pagename;
        $this->requestTimeout  = $core->settings()->findretros_timeout;
        $this->usingCloudFlare = $core->settings()->findretros_cloudflare;
        $this->apiPath         = $core->settings()->findretros_api;
        if($this->usingCloudFlare) {
            if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
                $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
            }
        }
    }
    public function hasClientVoted() {

        if(!$this->_isVoteCookieSet()) {
            $urlRequest = $this->apiPath . 'validate.php?user=' . $this->pageName . '&ip=' . $_SERVER['REMOTE_ADDR'];
            $dataRequest = $this->_makeCurlRequest($urlRequest); 
            if(in_array($dataRequest, array(1, 2))) {
                $this->_setVoteCookie();
                return true;
            }else if($dataRequest == 3) {
                return false;
            }else{
                /* There's something wrong with FindRetros, so we will mark the user as voted and have them proceed as if they voted. */
                $this->_setVoteCookie();
                return true;
            }
        }
        return true;
    }
    public function redirectClientToVote() {
        header('Location: ' . $this->apiPath . 'rankings/vote/' . $this->pageName);
        exit;
    }
    public function redirectClient() {
        return $this->apiPath . 'rankings/vote/' . $this->pageName;    
    }
    private function _makeCurlRequest($url) {
        if(function_exists('curl_version')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_TIMEOUT, $this->requestTimeout);
            curl_setopt($curl, CURLOPT_USERAGENT, 'FindRetros Vote Validator');
            $requestData = curl_exec($curl);
            curl_close($curl);
        }else{
            $requestData = stream_context_create(array('http' => array('timeout' => $this->requestTimeout)));
            return @file_get_contents($url, 0, $requestData);
        }
        return $requestData;
    }
    private function _setVoteCookie() {
        $rankingsResetTime = $this->_getRankingsResetTime();
        setcookie('voting_stamp', $rankingsResetTime, $rankingsResetTime);
    }
    private function _isVoteCookieSet() {
        if(isset($_COOKIE['voting_stamp'])) {
            if($_COOKIE['voting_stamp'] == $this->_getRankingsResetTime()) {
                return true;
            }else{
                setcookie('voting_stamp', '');
                return false;
            }
        }
        return false;
    }
    private function _getRankingsResetTime() {
        $serverDefaultTime = date_default_timezone_get();
        date_default_timezone_set('America/Chicago');
        $rankingsResetTime = mktime(0, 0, 0, date('n'), date('j') + 1);

        date_default_timezone_set($serverDefaultTime);
    
        return $rankingsResetTime;
    }
}