<?php

namespace App\Providers;

use App\Http\Repositories\ChatRepository;
use App\Http\Repositories\EntryRepository;
use App\Http\Repositories\HeaderRepository;
use App\Http\Repositories\MessageRepository;
use App\Http\Repositories\UserRepository;
use App\Http\RepositoryContracts\IChatRepository;
use App\Http\RepositoryContracts\IEntryRepository;
use App\Http\RepositoryContracts\IHeaderRepository;
use App\Http\RepositoryContracts\IMessageRepository;
use App\Http\RepositoryContracts\IUserRepository;
use App\Http\ServiceContracts\IEntryService;
use App\Http\ServiceContracts\IFollowService;
use App\Http\ServiceContracts\IHeaderService;
use App\Http\ServiceContracts\IMessageService;
use App\Http\ServiceContracts\IUserService;
use App\Http\Services\EntryService;
use App\Http\Services\FollowService;
use App\Http\Services\HeaderService;
use App\Http\Services\MessageService;
use App\Http\Services\UserService;
use App\Models\Chat;
use App\Models\Entry;
use App\Models\Header;
use App\Models\Message;
use App\Models\User;
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
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IMessageService::class, MessageService::class);
        $this->app->bind(IEntryService::class, EntryService::class);
        $this->app->bind(IHeaderService::class, HeaderService::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IMessageRepository::class, MessageRepository::class);
        $this->app->bind(IChatRepository::class, ChatRepository::class);
        $this->app->bind(IEntryRepository::class, EntryRepository::class);
        $this->app->bind(IHeaderRepository::class, HeaderRepository::class);
        $this->app->bind(IFollowService::class, FollowService::class);
    }
}
