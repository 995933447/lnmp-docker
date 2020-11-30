<?php

namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class GameApp extends BusinessConnectionModel
{
    const PHONE_APP_PLATFORM = 0;

    const H5_APP_PLATFORM = 1;

    const LOCAL_COUNTRY_BUSINESS_AREA = 0;

    const FOREIGN_AREA_BUSINESS_AREA = 1;

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function type()
    {
        return $this->hasOne(SupportGameType::class, 'id', 'game_type_id');
    }

    public static function transferAppPlatformDefinition(int $appPlatform): string
    {
        switch ($appPlatform) {
            case GameApp::H5_APP_PLATFORM:
                return 'H5';
            case GameApp::PHONE_APP_PLATFORM:
                return '手机';
        }
    }

    public static function transferBusinessAreaDefinition(int $businessArea): string
    {
        switch ($businessArea) {
            case GameApp::LOCAL_COUNTRY_BUSINESS_AREA:
                return '国内';
            case GameApp::FOREIGN_AREA_BUSINESS_AREA:
                return '海外';
        }
    }

    public static function generateNextCallbackKey(): string
    {
        return uniqid() . md5(microtime(true));
    }
}
