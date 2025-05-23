<?php

/**
 * OspfNbr.php
 *
 * -Description-
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @link       https://www.librenms.org
 *
 * @copyright  2018 Tony Murray
 * @author     Tony Murray <murraytony@gmail.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use LibreNMS\Interfaces\Models\Keyable;

class Ospfv3Nbr extends PortRelatedModel implements Keyable
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'device_id',
        'ospfv3_instance_id',
        'port_id',
        'router_id',
        'context_name',
        'ospfv3NbrIfIndex',
        'ospfv3NbrIfInstId',
        'ospfv3NbrRtrId',
        'ospfv3NbrAddress',
        'ospfv3NbrAddressType',
        'ospfv3NbrOptions',
        'ospfv3NbrPriority',
        'ospfv3NbrState',
        'ospfv3NbrEvents',
        'ospfv3NbrLsRetransQLen',
        'ospfv3NbmaNbrStatus',
        'ospfv3NbmaNbrPermanence',
        'ospfv3NbrHelloSuppressed',
        'ospfv3NbrIfId',
        'ospfv3NbrRestartHelperStatus',
        'ospfv3NbrRestartHelperAge',
        'ospfv3NbrRestartHelperExitReason',
    ];

    public function getCompositeKey(): string
    {
        return "$this->device_id-$this->ospfv3NbrIfIndex-$this->ospfv3NbrIfInstId-$this->ospfv3NbrRtrId-$this->context_name";
    }
}
