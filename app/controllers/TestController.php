<?php

class TestController extends BaseController
{
    public function test()
    {
        Queue::push('BatchController', ['id' => 6]);
        return Response::json('success');
    }


}
