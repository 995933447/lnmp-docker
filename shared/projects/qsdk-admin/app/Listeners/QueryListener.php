<?php
namespace App\Listeners;

use Illuminate\Database\Events\QueryExecuted;
use App\Fundations\Logger\Log;

class QueryListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(QueryExecuted $event)
    {
        if(config('app.sql_debug')) {
            foreach ($event->bindings as $key => $value) {
                if($value instanceof \DateTime) {
                    $event->bindings[$key] = $value->format("'Y-m-d H:i:s'");
                } else {
                    if(is_string($value)) {
                        $event->bindings[$key] = "'$value'";
                    }
                }
            }

            $sql = str_replace(['?', '%'], ['%s', '%%'], $event->sql);
            $sql = vsprintf($sql, $event->bindings);

            Log::debug("运行sql: $sql, args:" . json_encode($event->bindings));
            Log::debug("sql运行时间: {$event->time} ms");
        }
    }
}
