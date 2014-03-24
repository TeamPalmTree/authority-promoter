<?php

class Controller_Auth extends Controller_Hybrid
{

    protected $anonymous_rest_methods = array();
    protected $is_key_authenticated = false;
    protected $is_user_authenticated = false;
    protected $is_anonymous_authenticated = false;

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
        return parent::router($method, $params);

    }

    protected function authenticate($method)
    {

        // get some auth parameters
        $key = Input::get('key');
        // load promoter configuration
        Config::load('promoter', true);
        // get promoter key
        $promoter_key = Config::get('promoter.key');

        // if we have a key, validate against that
        if ($key == $promoter_key)
            $this->is_key_authenticated = true;
        else
            $this->is_user_authenticated = Auth::check();

        // get rest class
        $rest_class = get_class($this) . '.*';
        // get rest method from router method
        $rest_method = $rest_class . '.' . $method;
        // see if we have it in our lest of anon methods
        if (in_array($rest_class, $this->anonymous_rest_methods)
            or in_array($rest_method, $this->anonymous_rest_methods))
        {
            // force input to rest status
            $_SERVER['HTTP_X_REQUESTED_WITH'] = 'XMLHttpRequest';
            // we are anonymous
            $this->is_anonymous_authenticated = true;
        }

    }

}
