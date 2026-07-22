<?php

namespace React\Promise\Stream;

use Evenement\EventEmitter;
use InvalidArgumentException;
use React\Promise\PromiseInterface;
use React\Stream\ReadableStreamInterface;
use React\Stream\Util;
use React\Stream\WritableStreamInterface;

/**
 * @internal
 * @see unwrapReadable() instead
 */
class UnwrapReadableStream extends EventEmitter implements ReadableStreamInterface
{
    private $promise;
    private $closed = false;

    /**
     * Instantiate new unwrapped readable stream for given `Promise` which resolves with a `ReadableStreamInterface`.
     *
     * @param PromiseInterface $promise Promise<ReadableStreamInterface, Exception>
     */
    public function __construct(PromiseInterface $promise)
    {
        $out = $this;
        $closed =& $this->closed;

        $this->promise = $promise->then(
            function ($stream) {
                if (!$stream instanceof ReadableStreamInterface) {
                    throw new InvalidArgumentException('Not a readable stream');
***REMOVED***
                return $stream;
***REMOVED***
        )->then(
            function (ReadableStreamInterface $stream) use ($out, &$closed) {
                // stream is already closed, make sure to close output stream
                if (!$stream->isReadable()) {
                    $out->close();
                    return $stream;
***REMOVED***

                // resolves but output is already closed, make sure to close stream silently
                if ($closed) {
                    $stream->close();
                    return $stream;
***REMOVED***

                // stream any writes into output stream
                $stream->on('data', function ($data) use ($out) {
                    $out->emit('data', array($data, $out));
***REMOVED***);

                // forward end events and close
                $stream->on('end', function () use ($out, &$closed) {
                    if (!$closed) {
                        $out->emit('end', array($out));
                        $out->close();
  ***REMOVED***
***REMOVED***);

                // error events cancel output stream
                $stream->on('error', function ($error) use ($out) {
                    $out->emit('error', array($error, $out));
                    $out->close();
***REMOVED***);

                // close both streams once either side closes
                $stream->on('close', array($out, 'close'));
                $out->on('close', array($stream, 'close'));

                return $stream;
***REMOVED***,
            function ($e) use ($out, &$closed) {
                if (!$closed) {
                    $out->emit('error', array($e, $out));
                    $out->close();
***REMOVED***

                // resume() and pause() may attach to this promise, so ensure we actually reject here
                throw $e;
***REMOVED***
        );
    }

    public function isReadable()
    {
        return !$this->closed;
    }

    public function pause()
    {
        if ($this->promise !== null) {
            $this->promise->then(function (ReadableStreamInterface $stream) {
                $stream->pause();
***REMOVED***);
        }
    }

    public function resume()
    {
        if ($this->promise !== null) {
            $this->promise->then(function (ReadableStreamInterface $stream) {
                $stream->resume();
***REMOVED***);
        }
    }

    public function pipe(WritableStreamInterface $dest, array $options = array())
    {
        Util::pipe($this, $dest, $options);

        return $dest;
    }

    public function close()
    {
        if ($this->closed) {
            return;
        }

        $this->closed = true;

        // try to cancel promise once the stream closes
        if ($this->promise !== null && \method_exists($this->promise, 'cancel')) {
            $this->promise->cancel();
        }
        $this->promise = null;

        $this->emit('close');
        $this->removeAllListeners();
    }
}
