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
    public function boot() : void
    {
        Model::shouldBeStrict(!app()->isProduction());
        if(app()->isProduction()) {
            DB::listen(static function ($query){

                if($query->time>100 ){
                    logger()
                        ->channel('telegram')
                        ->debug('longer than 1 ms:'. $query->sql, $query->bindings);
                }
            });

            app(Kernel::class)->whenRequestLifeCycleIsLongerThan(
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
