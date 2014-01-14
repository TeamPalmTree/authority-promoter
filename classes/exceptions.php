<?php

class HttpAccessDeniedException extends \Fuel\Core\HttpException
{


    /**
     * Must return a response object for the handle method
     *
     * @return  \Fuel\Core\Response
     */
    protected function response()
    {
        return new \Fuel\Core\Response(\Fuel\Core\View::forge('403'), 403);
    }
}