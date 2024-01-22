<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::shouldBeStrict(!app()->isProduction());
        if(app()->isProduction()) {
            DB::whenQueryingForLongerThan(CarbonInterval::seconds(5), function (Connection $connection) {

                logger()
                    ->channel('telegram')
                    ->debug('whenQueryingForLongerThan' . $connection->totalQueryDuration());

            });

            DB::listen(static function ($query){

                if($query->time>100 ){
                    logger()
                        ->channel('telegram')
                        ->debug('whenQueryingForLongerThan'. $query->sql, $query->bindings);
                }
            });

            $kernel = app(Kernel::class);
            $kernel->whenRequestLifeCycleIsLongerThan(
                CarbonInterval::seconds(4),
                    function () {
                    logger()
                        ->channel('telegram')
                        ->debug('whenRequestLifeCycleIsLongerThan' . request()->url());
                }
            );
        }
    }
}
