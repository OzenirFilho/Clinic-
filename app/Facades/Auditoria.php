<?php
namespace App\Facades;

class Auditoria extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor()
    {
        //The acessor is here
        return 'auditoria';
    }
}
