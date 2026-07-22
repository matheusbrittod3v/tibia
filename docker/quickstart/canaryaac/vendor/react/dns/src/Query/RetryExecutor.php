<?php

namespace React\Dns\Query;

use React\Promise\CancellablePromiseInterface;
use React\Promise\Deferred;
use React\Promise\PromiseInterface;

final class RetryExecutor implements ExecutorInterface
{
    private $executor;
    private $retries;

    public function __construct(ExecutorInterface $executor, $retries = 2)
    {
        $this->executor = $executor;
        $this->retries = $retries;
    }

    public function query(Query $query)
    {
        return $this->tryQuery($query, $this->retries);
    }

    public function tryQuery(Query $query, $retries)
    {
        $deferred = new Deferred(function () use (&$promise) {
            if ($promise instanceof CancellablePromiseInterface || (!\interface_exists('React\Promise\CancellablePromiseInterface') && \method_exists($promise, 'cancel'))) {
                $promise->cancel();
***REMOVED***
        });

        $success = function ($value) use ($deferred, &$errorback) {
            $errorback = null;
            $deferred->resolve($value);
        };

        $executor = $this->executor;
        $errorback = function ($e) use ($deferred, &$promise, $query, $success, &$errorback, &$retries, $executor) {
            if (!$e instanceof TimeoutException) {
                $errorback = null;
                $deferred->reject($e);
***REMOVED*** elseif ($retries <= 0) {
                $errorback = null;
                $deferred->reject($e = new \RuntimeException(
                    'DNS query for ' . $query->describe() . ' failed: too many retries',
                    0,
                    $e
                ));

                // avoid garbage references by replacing all closures in call stack.
                // what a lovely piece of code!
                $r = new \ReflectionProperty('Exception', 'trace');
                $r->setAccessible(true);
                $trace = $r->getValue($e);

                // Exception trace arguments are not available on some PHP 7.4 installs
                // @codeCoverageIgnoreStart
                foreach ($trace as &$one) {
                    if (isset($one['args'])) {
                        foreach ($one['args'] as &$arg) {
                            if ($arg instanceof \Closure) {
                                $arg = 'Object(' . \get_class($arg) . ')';
          ***REMOVED***
      ***REMOVED***
  ***REMOVED***
***REMOVED***
                // @codeCoverageIgnoreEnd
                $r->setValue($e, $trace);
***REMOVED*** else {
                --$retries;
                $promise = $executor->query($query)->then(
                    $success,
                    $errorback
                );
***REMOVED***
        };

        $promise = $this->executor->query($query)->then(
            $success,
            $errorback
        );

        return $deferred->promise();
    }
}
