<?php

namespace App\Providers;

use App\Models\AlertSubscription;
use App\Models\Message;
use App\Models\Product;
use App\Models\Research;
use App\Models\Translation;
use App\Models\User;
use App\Observers\AlertSubscriptionObserver;
use App\Observers\MessageObserver;
use App\Observers\ProductObserver;
use App\Observers\ResearchObserver;
use App\Observers\TranslationObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;

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
        Research::observe(ResearchObserver::class);
        Product::observe(ProductObserver::class);
        AlertSubscription::observe(AlertSubscriptionObserver::class);
        User::observe(UserObserver::class);
        Message::observe(MessageObserver::class);
        $this->app->singleton(StripeClient::class, function () {
            return new StripeClient(config("services.stripe.secret"));
        });
    }
}
