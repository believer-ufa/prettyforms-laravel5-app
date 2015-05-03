<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use PrettyFormsLaravel\FormProcessLogic;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests, FormProcessLogic;
    
    protected $redirectTo = '/';

}
