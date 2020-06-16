<?php

namespace App\Observers;

use App\Entidades\Sca\M0001 as User;

class ObserverUsuario
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Entidades\Sca\User  $User
     * @return void
     */
    public function created(User $user)
    {
        //Definir o status do usuário como não inicializado.

    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Entidades\Sca\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Entidades\Sca\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the m0001 "restored" event.
     *
     * @param  \App\Entidades\Sca\M0001  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param  \App\Entidades\Sca\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
