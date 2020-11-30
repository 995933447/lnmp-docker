<?php
namespace App\Models\Statistics\LTV;

use App\Models\GameApp;
use App\Models\PostChannel;

class DailySpecificGameChannelLtvStatistics extends LTVStatisticsModel
{
    const ID_FIELD = 'id';

    const GAME_APP_ID_FIELD = 'game_app_id';

    const POST_CHANNEL_ID_FIELD = 'post_channel_id';

    const BELONG_DATE_FIELD = 'belong_date';

    public function game()
    {
        return $this->belongsTo(GameApp::class, 'game_app_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo(PostChannel::class, "post_channel_id", 'id');
    }
}
