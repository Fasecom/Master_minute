<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Blade::directive('formatPhone', function ($phone) {
            return "<?php echo preg_replace('/^(7)(\d{3})(\d{3})(\d{2})(\d{2})$/', '+7 $2 $3-$4-$5', $phone); ?>";
        });
    }
}
