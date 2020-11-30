<?php
namespace App\Models\Statistics;

use App\Models\Connections\StatisticsConnectionModel;
use App\Models\GameApp;
use App\Models\PostChannel;

class DailySpecificGameAccountDataTrendStatistics extends StatisticsConnectionModel
{
    const ID_FILED = 'id';

    const AGGREGATED_AT_FIELD = 'aggregated_at';

    const GAME_APP_ID_FIELD = 'game_app_id';

    const BELONG_DATE_FILED = 'belong_date';

    public function game()
    {
        return $this->belongsTo(GameApp::class, 'game_app_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo(PostChannel::class, "post_channel_id", 'id');
    }
}
