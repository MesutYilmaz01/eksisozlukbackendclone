<?php

namespace App\Providers;

use App\Http\Repositories\ChatRepository;
use App\Http\Repositories\DeletedChatRepository;
use App\Http\Repositories\MessageRepository;
use App\Http\Repositories\UserRepository;
use App\Http\RepositoryContracts\IChatRepository;
use App\Http\RepositoryContracts\IDeletedChatRepository;
use App\Http\RepositoryContracts\IMessageRepository;
use App\Http\RepositoryContracts\IUserRepository;
use App\Http\ServiceContracts\IChatService;
use App\Http\ServiceContracts\IDeletedChatService;
use App\Http\ServiceContracts\IMessageService;
use App\Http\ServiceContracts\IUserService;
use App\Http\Services\ChatService;
use App\Http\Services\DeletedChatService;
use App\Http\Services\MessageService;
use App\Http\Services\UserService;
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
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IMessageService::class, MessageService::class);
        $this->app->bind(IMessageRepository::class, MessageRepository::class);
        $this->app->bind(IChatService::class, ChatService::class);
        $this->app->bind(IChatRepository::class, ChatRepository::class);
    }
}
