<?php

class Controller_Auth extends Controller_Hybrid
{

    protected $anonymous_methods = array();
    protected $is_anonymous = false;
    protected $is_authenticated = false;
    protected $is_restful = false;

    public function before()
    {
        // forward
        parent::before();
        // set restful (initially from request)
        $this->is_restful = $this->is_restful();
    }

    public function router($method, $params)
    {

        // authenticate
        $this->authenticate($method);
        // forward
        parent::router($method, $params);

    }

    protected function authenticate($method)
    {

        // get some auth parameters
        $key = Input::get('key');
        $rest_method = get_class($this) . '.' . $method;
        // load promoter configuration
        Config::load('promoter', true);
        // get promoter key
        $promoter_key = Config::get('promoter.key');

        ///////////////
        // KEY CHECK //
        ///////////////

        // if we have a key, validate against that
        if ($key == $promoter_key)
        {
            // we are restful
            $this->is_restful = true;
        }

        //////////////////////////
        // ANONYMOUS REST CHECK //
        //////////////////////////

        if (in_array($rest_method, $this->anonymous_methods))
        {
            // we are restful
            $this->is_restful = true;
            // we are anonymous
            $this->is_anonymous = true;
        }

        ////////////////
        // AUTH CHECK //
        ////////////////

        // success
        $this->is_authenticated = Auth::check();

    }

}
