<?php

namespace React\Promise;

class CancellationQueue
{
    private $started = false;
    private $queue = [];

    public function __invoke()
    {
        if ($this->started) {
            return;
        }

        $this->started = true;
        $this->drain();
    }

    public function enqueue($cancellable)
    {
        if (!\is_object($cancellable) || !\method_exists($cancellable, 'then') || !\method_exists($cancellable, 'cancel')) {
            return;
        }

        $length = \array_push($this->queue, $cancellable);

        if ($this->started && 1 === $length) {
            $this->drain();
        }
    }

    private function drain()
    {
        for ($i = key($this->queue); isset($this->queue[$i]); $i++) {
            $cancellable = $this->queue[$i];

            $exception = null;

            try {
                $cancellable->cancel();
***REMOVED*** catch (\Throwable $exception) {
***REMOVED*** catch (\Exception $exception) {
***REMOVED***

            unset($this->queue[$i]);

            if ($exception) {
                throw $exception;
***REMOVED***
        }

        $this->queue = [];
    }
}
