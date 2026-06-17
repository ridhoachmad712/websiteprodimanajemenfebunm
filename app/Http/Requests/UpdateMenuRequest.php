<?php

namespace App\Http\Requests;

/**
 * Aturan validasi sama dengan StoreMenuRequest. Pencegahan parent = diri
 * sendiri ditangani di controller.
 */
class UpdateMenuRequest extends StoreMenuRequest
{
}
