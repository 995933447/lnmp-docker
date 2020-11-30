<?php

use Illuminate\Routing\Router;

use Illuminate\Http\Request;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('game-apps', GameAppController::class);
    $router->resource('support-game-types', SupportGameTypeController::class);
    $router->resource('post-channels', PostChannelController::class);
    $router->resource('post-channel-sdk-versions', PostChannelSdkVersionController::class);
    $router->resource('game-app-posted-channels', GameAppPostedChannelController::class);
    $router->resource('game-app-posted-channel-configs', GameAppPostedChannelConfigController::class);
    $router->resource('game-app-posted-channel-arguments', GameAppPostedChannelArgumentController::class);
    $router->resource('post-channel-arguments', PostChannelArgumentController::class);
    $router->resource('register-player-logs', RegiterPlayerLogController::class);
    $router->resource('player-login-logs', PlayerLoginLogController::class);
    $router->resource('player-create-game-role-logs', PlayerCreateGameRoleLogController::class);
    $router->resource('player-game-role-enter-game-logs', PlayerGameRoleEnterGameLogController::class);
    $router->resource('order-logs', OrderLogController::class);
    $router->resource('notify-cp-order-finish-logs', NotifyCPOrderFinishLogController::class);

    $router->get('/game-app-posted-channel/setting', 'GameAppPostedChannelArgumentController@setting');
    $router->any('/post-channel-argument/setting/{postChannelId}', 'PostChannelArgumentController@setting');

    $router->group(['prefix' => '/api'], function (Router $router) {
        $router->get('/game_apps', 'Api\GameAppController@queryGameAppsOfUser');
        $router->get('/post-channel/sdk-version', 'Api\PostChannelSdkVersionController@querySdkVersionsOfPostChannel');
    });

    $router->resource('daily-game-channel-account-trend', DailySpecificGameChannelAccountDataTrendStatisticsController::class);

    $router->resource('daily-game-channel-charged-trend', DailySpecificGameChannelChargedTrendStatisticsController::class);

    $router->resource('daily-game-channel-retention', DailySpecificGameChannelPlayersRetentionStatisticsController::class);

    $router->resource('daily-game-channel-ltv', DailySpecificGameChannelLtvStatisticsController::class);

    $router->resource('game-app-testable-white-ips', GameAppTestableWhiteIpController::class);
});
