<?php

namespace App\Policies;

use App\Http\Enums\MessageSendTypes;
use App\Http\Enums\UserTypeEnums;
use App\Http\RepositoryContracts\IUserRepository;
use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MessagePolicy
{
    use HandlesAuthorization;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function sendMessage(User $user, array $data)
    {
        $user = $this->userRepository->getByUsername($data['username']);
        
        if($user->message_permit_type == MessageSendTypes::ADMINS_AND_MODERATORS_AND_USERS->value 
            && auth()->user()->user_type == UserTypeEnums::NEWBIE->value) {
                return Response::deny('Newbies cant send message to this user.', 400);
        }else if($user->message_permit_type == MessageSendTypes::ADMINS_AND_MODERATORS->value 
            && (auth()->user()->user_type == UserTypeEnums::USER->value || auth()->user()->user_type 
            == UserTypeEnums::NEWBIE->value)) {
                return Response::deny('Users cant send message to this user.', 400);
        }else if($user->message_permit_type == MessageSendTypes::ONLY_ADMINS->value 
            && auth()->user()->user_type != UserTypeEnums::ADMIN->value) {
                return Response::deny('Moderators cant send message to this user.', 400);
        }
        
        return Response::allow();
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Message $message)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Message $message)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Message $message)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Message $message)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Message $message)
    {
        //
    }
}
