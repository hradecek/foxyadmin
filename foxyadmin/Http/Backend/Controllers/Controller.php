<?php

namespace Foxytouch\Http\Backend\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * <p>
 * Abstract base <b>controller</b> class
 * </p>
 *
 * @package Foxytouch\Http\Backend\Controllers
 * @author Ivo Hradek <ivohradek@gmail.com>
 */
abstract class Controller extends BaseController
{
    /* TODO: What's this? */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
