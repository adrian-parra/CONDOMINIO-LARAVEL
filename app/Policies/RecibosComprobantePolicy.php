<?php

namespace App\Policies;

use App\Models\RecibosComprobante;
use App\Models\usuario;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecibosComprobantePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(usuario $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\recibos_comprobante  $recibosComprobante
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(usuario $user, RecibosComprobante $recibosComprobante)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(usuario $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\recibos_comprobante  $recibosComprobante
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(usuario $user, RecibosComprobante $recibosComprobante)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\recibos_comprobante  $recibosComprobante
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(usuario $user, RecibosComprobante $recibosComprobante)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\recibos_comprobante  $recibosComprobante
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(usuario $user, RecibosComprobante $recibosComprobante)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\recibos_comprobante  $recibosComprobante
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(usuario $user, RecibosComprobante $recibosComprobante)
    {
        //
    }
}
