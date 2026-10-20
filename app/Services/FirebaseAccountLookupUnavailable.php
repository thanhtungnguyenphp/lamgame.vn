<?php

namespace App\Services;

use RuntimeException;

/**
 * An infrastructure/configuration failure must not be reported to the client
 * as an invalid Firebase credential. The client can safely retry a 503, while
 * a 401 requires reauthentication.
 */
class FirebaseAccountLookupUnavailable extends RuntimeException {}
