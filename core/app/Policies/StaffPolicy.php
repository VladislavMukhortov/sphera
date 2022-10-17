<?php

namespace App\Policies;

use App\Models\Staff;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffPolicy
{
    use HandlesAuthorization;

    public function before(Staff $staff)
    {
        if ($staff->isSuperAdmin()) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Staff  $staff
     *
     * @return mixed
     */
    public function viewAny(Staff $staff)
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Staff  $staff
     *
     * @return mixed
     */
    public function view(Staff $staff)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Staff  $staff
     *
     * @return mixed
     */
    public function create(Staff $staff)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Staff  $staff
     *
     * @return mixed
     */
    public function update(Staff $staff)
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Staff  $staff
     *
     * @return mixed
     */
    public function delete(Staff $staff)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Staff  $staff
     *
     * @return mixed
     */
    public function restore(Staff $staff)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Staff  $staff
     *
     * @return mixed
     */
    public function forceDelete(Staff $staff)
    {
        return false;
    }
}
