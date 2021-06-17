<?php
namespace App\Traits;
use App\Models\Log;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

trait UserDataTrait
{
    public function getDataUser(Request $request)
    {
        $ip = $this->getIpList($request);
        $ipInfo = $this->getCity($ip);
        if($ipInfo['status'] == 'success') {
            $city = $ipInfo['city'];
            $device = null;
            $agent = new Agent();
            if ($agent->isMobile() || $agent->isTablet()){
                $device = $agent->device().'-'.$agent->platform();
            }
            if ($agent->isDesktop()) {
                $device = 'Desktop-'.$agent->platform();
            }
            $browser = $agent->browser();
            Log::create([
                'ip' => $ip,
                'device' => $device,
                'browser' => $browser,
                'city' => $city,
            ]);
        }
        return $ipInfo;
    }

    private function getIpList(Request $request)
    {
        $list = array();
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $list = array_merge($list, $ip);
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $list = array_merge($list, $ip);
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $list[] = $_SERVER['REMOTE_ADDR'];
        }
        $list = array_unique($list);
        return implode(',', $list);
    }

    private function getCity($ip)
    {
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, 'http://ip-api.com/json/'.$ip.'?lang=en');
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        $userGeoData = json_decode(curl_exec($curl_handle), true);
        curl_close($curl_handle);
        return $userGeoData;
    }

}