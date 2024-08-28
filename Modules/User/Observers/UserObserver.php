<?php

namespace Modules\User\Observers;

use Modules\User\Entities\User;

class UserObserver
{

    protected $userService;
    public function __construct(\Modules\User\Services\UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(User $user): void
    {
        //
    }
    /**
     * Handle the Order "updating" event.
     */
    public function updating(User $user)
    {
        $newEmail = trim($user->email);
        $originalEmail = trim($user->getOriginal('email'));
        if (strcasecmp($newEmail, $originalEmail) !== 0) {
            $token = \App\Helpers\Helper::randomOTP(32);
            $user->token_change_email = $token;
            $this->userService->changeEmailUser($user->fullname, $originalEmail, $newEmail, $token);
            $user->email = $originalEmail;
            $user->email_change_request = $newEmail;
        }
    }
}
