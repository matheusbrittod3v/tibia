<?php
/**
 * Validator class
 *
 * @package   CanaryAAC
 * @author    Lucas Giovanni <lucasgiovannidesigner@gmail.com>
 * @copyright 2022 CanaryAAC
 */

    namespace App\Http;

    class Response
    {
        private $httpCode = 200;

        private $headers = [];

        private $contentType = 'text/html';

        private $content;

        public function __construct($httpCode, $content, $contentType = 'text/html')
***REMOVED***
            $this->httpCode = $httpCode;
            $this->content = $content;
            $this->setContentType($contentType);
        }

        public function setContentType($contentType)
***REMOVED***
            $this->contentType = $contentType;
            $this->addHeader('Content-Type', $contentType);
        }

        public function addHeader($key, $value)
***REMOVED***
            $this->headers[$key] = $value;
        }

        private function sendHeaders()
***REMOVED***
            http_response_code($this->httpCode);
            foreach($this->headers as $key => $value)
    ***REMOVED***
                header($key.': '.$value);
***REMOVED***
        }

        public function sendResponse()
***REMOVED***
            $this->sendHeaders();
            switch($this->contentType)
    ***REMOVED***
                case 'text/html':
                    echo $this->content;
                    exit;
                case 'application/json':
                    echo json_encode($this->content, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    exit;
***REMOVED***
        }
    }