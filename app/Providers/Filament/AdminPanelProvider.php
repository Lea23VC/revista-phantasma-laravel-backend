<?php

namespace App\Providers\Filament;

use App\Models\Category;
use App\Models\Page;
use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RyanChandler\FilamentNavigation\FilamentNavigation;
use Filament\Forms\Components\Select;
use App\Models\Post;
use Marjose123\FilamentWebhookServer\WebhookPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->brandLogo(asset('images/phantasma_white.png'))
            ->brandLogoHeight('40px')
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
            ])->plugins([FilamentNavigation::make()


                ->itemType('Pages', [
                    Select::make('url')->label('Select Page')
                        ->searchable()
                        ->options(function () {
                            return Page::pluck('title', 'slug');
                        })
                ])
                ->itemType('Posts', [

                    Select::make('url')->label('Select Page')
                        ->searchable()
                        ->options(function () {
                            return Page::pluck('title', 'slug');
                        }),


                ])->itemType('Categories', [
                    Select::make('url')->label('Select Category')
                        ->searchable()
                        ->options(function () {
                            return Category::pluck('name', 'slug');
                        }),

                ]), WebhookPlugin::make(), new \RickDBCN\FilamentEmail\FilamentEmail()]);
    }
}
