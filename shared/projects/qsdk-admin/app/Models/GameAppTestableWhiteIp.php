<?php
namespace App\Models;

use App\Models\Connections\BusinessConnectionModel;

class GameAppTestableWhiteIp extends BusinessConnectionModel
{
    const GAME_APP_ID_FIELD = 'game_app_id';

    const IP_FIELD = 'ip';

    const ID_FIELD = 'id';

    const VALID_STATUS = 1;

    const INVALID_STATUS = 0;

    public static function transferStatusDefinition(int $status)
    {
        switch ($status) {
            case static::VALID_STATUS:
                return '启用';
            case static::INVALID_STATUS:
                return '未启用';
        }
    }

    public function game()
    {
        return $this->belongsTo(GameApp::class, 'game_app_id', 'id');
    }
}
