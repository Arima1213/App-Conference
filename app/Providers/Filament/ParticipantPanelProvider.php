<?php

namespace App\Providers\Filament;

use App\Filament\Participant\Pages\PaymentPage;
use App\Filament\Participant\Resources\ParticipantProfileWidgetResource\Widgets\ParticipantProfileWidget;
use App\Filament\Participant\Resources\ParticipantQRCodeWidgetResource\Widgets\ParticipantQRCodeWidget;
use App\Filament\Participant\Resources\ParticipantSeminarKitWidgetResource\Widgets\ParticipantSeminarKitWidget;
use App\Filament\Participant\Resources\RegisterConferenceeWidgetResource\Widgets\RegisterConferenceeWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class ParticipantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('participant')
            ->topNavigation()
            ->sidebarCollapsibleOnDesktop()
            ->login()
            ->databaseNotifications()
            ->profile()
            ->passwordReset()
            ->emailVerification()
            ->registration()
            ->spa()
            ->path('participant')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Participant/Resources'), for: 'App\\Filament\\Participant\\Resources')
            ->discoverPages(in: app_path('Filament/Participant/Pages'), for: 'App\\Filament\\Participant\\Pages')
            ->pages([
                Pages\Dashboard::class,
                PaymentPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Participant/Widgets'), for: 'App\\Filament\\Participant\\Widgets')
            ->widgets([
                RegisterConferenceeWidget::class,
                ParticipantProfileWidget::class,
                ParticipantQRCodeWidget::class,
                ParticipantSeminarKitWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}