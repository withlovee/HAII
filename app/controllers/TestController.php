<?php

use Symfony\Component\Process\Process;

class TestController extends BaseController
{
    public function test()
    {
        $process = new Process('ls -lsa');
        $process->start();

        // executes after the command finishes
        if (!$process->isSuccessful()) {
            throw new \RuntimeException($process->getErrorOutput());
        }

        $output = $process->getOutput();

        return Response::json($output);
    }
}
