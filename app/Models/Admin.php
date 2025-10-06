<?php

namespace App\Models;

use App\Traits\HasExtendedProfile;
use Webkul\User\Models\Admin as BagistoAdmin;

class Admin extends BagistoAdmin
{
    use HasExtendedProfile;

    // Inherit all functionality from Bagisto Admin
    // and add extended profile capabilities through trait
}