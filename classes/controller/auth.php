<?php

class Controller_Auth extends Controller_Hybrid
{

    protected static $anonymous_rest_methods = array();

    protected $is_restful = false;

    public function router($method, $params)
    {

        //////////////////
        // AUTHENTICATE //
        //////////////////

        $this->authenticate($method);

        /////////////
        // FORWARD //
        /////////////

        // forward to router
        parent::router($method, $params);

    }

    protected function authenticate($method)
    {

        // get some auth parameters
        $key = Input::get('key');
        $rest_method = get_class($this) . '.' . $method;
        // load authority configuration
        Config::load('authority', true);
        // get authrity key
        $authority_key = Config::get('authority.key');

        ///////////////
        // KEY CHECK //
        ///////////////

        // if we have a key, validate against that
        // else validate againt simpleauth
        if ($key == $authority_key)
        {
            // we are restful & authorized
            $this->is_restful = true;
            return;
        }

        //////////////////////////
        // ANONYMOUS REST CHECK //
        //////////////////////////

        if (in_array($rest_method, self::$anonymous_rest_methods))
        {
            // we are restful & authorized
            $this->is_restful = true;
            return;
        }

        ////////////////
        // AUTH CHECK //
        ////////////////

        if (!Auth::check())
        {
            // we failed to authorize
            Response::redirect();
            return;
        }

    }

}
