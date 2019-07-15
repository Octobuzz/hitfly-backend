<?php

namespace App\Providers;

use App\Listeners\BonusProgramEventSubscriber;
use App\Listeners\MainCollectionEventSubscriber;
use App\Listeners\NotificationEventSubscriber;
use App\Listeners\TrackListenSubscriber;
use App\Listeners\UserEventSubscriber;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //Registered::class => [
        //    SendEmailVerificationNotification::class,
        //],
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            'SocialiteProviders\VKontakte\VKontakteExtendSocialite@handle',
            //'SocialiteProviders\Odnoklassniki\OdnoklassnikiExtendSocialite@handle',
            \JhaoDa\SocialiteProviders\Odnoklassniki\OdnoklassnikiExtendSocialite::class,
            'SocialiteProviders\Instagram\InstagramExtendSocialite@handle',
            'SocialiteProviders\Facebook\FacebookExtendSocialite@handle',
        ],
        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LastLoginLog',
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserEventSubscriber::class,
        BonusProgramEventSubscriber::class,
        NotificationEventSubscriber::class,
        MainCollectionEventSubscriber::class,
        TrackListenSubscriber::class,
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    }
}
