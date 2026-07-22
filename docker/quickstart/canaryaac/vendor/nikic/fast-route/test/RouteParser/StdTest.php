<?php

namespace FastRoute\RouteParser;

use PHPUnit\Framework\TestCase;

class StdTest extends TestCase
{
    /** @dataProvider provideTestParse */
    public function testParse($routeString, $expectedRouteDatas)
    {
        $parser = new Std();
        $routeDatas = $parser->parse($routeString);
        $this->assertSame($expectedRouteDatas, $routeDatas);
    }

    /** @dataProvider provideTestParseError */
    public function testParseError($routeString, $expectedExceptionMessage)
    {
        $parser = new Std();
        $this->setExpectedException('FastRoute\\BadRouteException', $expectedExceptionMessage);
        $parser->parse($routeString);
    }

    public function provideTestParse()
    {
        return [
            [
                '/test',
                [
                    ['/test'],
    ***REMOVED***
  ***REMOVED***
            [
                '/test/{param}',
                [
                    ['/test/', ['param', '[^/]+']],
    ***REMOVED***
  ***REMOVED***
            [
                '/te{ param }st',
                [
                    ['/te', ['param', '[^/]+'], 'st']
    ***REMOVED***
  ***REMOVED***
            [
                '/test/{param1}/test2/{param2}',
                [
                    ['/test/', ['param1', '[^/]+'], '/test2/', ['param2', '[^/]+']]
    ***REMOVED***
  ***REMOVED***
            [
                '/test/{param:\d+}',
                [
                    ['/test/', ['param', '\d+']]
    ***REMOVED***
  ***REMOVED***
            [
                '/test/{ param : \d{1,9} }',
                [
                    ['/test/', ['param', '\d{1,9}']]
    ***REMOVED***
  ***REMOVED***
            [
                '/test[opt]',
                [
                    ['/test'],
                    ['/testopt'],
    ***REMOVED***
  ***REMOVED***
            [
                '/test[/{param}]',
                [
                    ['/test'],
                    ['/test/', ['param', '[^/]+']],
    ***REMOVED***
  ***REMOVED***
            [
                '/{param}[opt]',
                [
                    ['/', ['param', '[^/]+']],
                    ['/', ['param', '[^/]+'], 'opt']
    ***REMOVED***
  ***REMOVED***
            [
                '/test[/{name}[/{id:[0-9]+}]]',
                [
                    ['/test'],
                    ['/test/', ['name', '[^/]+']],
                    ['/test/', ['name', '[^/]+'], '/', ['id', '[0-9]+']],
    ***REMOVED***
  ***REMOVED***
            [
                '',
                [
                    [''],
    ***REMOVED***
  ***REMOVED***
            [
                '[test]',
                [
                    [''],
                    ['test'],
    ***REMOVED***
  ***REMOVED***
            [
                '/{foo-bar}',
                [
                    ['/', ['foo-bar', '[^/]+']]
    ***REMOVED***
  ***REMOVED***
            [
                '/{_foo:.*}',
                [
                    ['/', ['_foo', '.*']]
    ***REMOVED***
  ***REMOVED***
        ];
    }

    public function provideTestParseError()
    {
        return [
            [
                '/test[opt',
                "Number of opening '[' and closing ']' does not match"
  ***REMOVED***
            [
                '/test[opt[opt2]',
                "Number of opening '[' and closing ']' does not match"
  ***REMOVED***
            [
                '/testopt]',
                "Number of opening '[' and closing ']' does not match"
  ***REMOVED***
            [
                '/test[]',
                'Empty optional part'
  ***REMOVED***
            [
                '/test[[opt]]',
                'Empty optional part'
  ***REMOVED***
            [
                '[[test]]',
                'Empty optional part'
  ***REMOVED***
            [
                '/test[/opt]/required',
                'Optional segments can only occur at the end of a route'
  ***REMOVED***
        ];
    }
}
