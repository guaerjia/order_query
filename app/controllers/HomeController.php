<?php
/**
 * Created by PhpStorm.
 * User: 4003310
 * Date: 2014/11/12
 * Time: 11:50
 */
class HomeController extends BaseController {

    public function home() {

        return View::make('home');

    }
}