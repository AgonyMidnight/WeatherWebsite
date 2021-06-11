<?php
namespace App\Traits;
use Jenssegers\Agent\Agent;

trait UserDataTrait
{
    private function setDevice()
    {
        $agent = new Agent();
        /*if ($agent->isMobile() || $agent->isTablet()){
            return response()->json(['data' => $agent->device()]);
        }
        else {
            return response()->json(['data' => $agent->platform()]);
        }*/
        $platform = $agent->platform();
        $version = $agent->version($platform);
        return response()->json(['data' => $platform]);
    }

    public function getData()
    {
        return $this->setDevice();
    }

}