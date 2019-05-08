<?php
/**
 * Created by PhpStorm.
 * User: Mpande Andrew
 * Date: 08/05/2019
 * Time: 13:16
 */

namespace FannyPack\LaracombeeMultiDB;

use Illuminate\Support\Facades\Facade;

class MultiDBFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laracombee-multi-db';
    }
}