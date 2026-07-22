<?php
/**
 * Request Class
 *
 * @package   CanaryAAC
 * @author    Lucas Giovanni <lucasgiovannidesigner@gmail.com>
 * @copyright 2022 CanaryAAC
 */

    namespace App\Http;

    class Request
    {
        private $router;

        private $httpMethod;

        private $uri;

        private $queryParams = [];

        private $postVars = [];

        private $postFiles = [];

        private $headers = [];

        public function __construct($router)
***REMOVED***
            $this->router = $router;
            $this->queryParams = $_GET ?? [];
            $this->headers = getallheaders();
            $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
            $this->setUri();
            $this->setPostVars();
        }

        private function setPostVars(){
            if($this->httpMethod == 'GET') return false;

            $this->postVars = $_POST ?? [];
            $inputRaw = file_get_contents('php://input');
            $this->postVars = (strlen($inputRaw) && empty($_POST)) ? json_decode($inputRaw, true) : $this->postVars;

            $this->postFiles = $_FILES ?? [];
            $this->postFiles = (strlen($inputRaw) && empty($_FILES)) ? json_decode($inputRaw, true) : $this->postFiles;
        }

        private function setUri(){
            $this->uri = $_SERVER['REQUEST_URI'] ?? '';

            $xUri = explode('?', $this->uri);
            $this->uri = $xUri[0];
        }

        public function getRouter()
***REMOVED***
            return $this->router;
        }

        public function getHttpMethod()
***REMOVED***
            return $this->httpMethod;
        }

        public function getUri()
***REMOVED***
            return $this->uri;
        }

        public function getHeaders()
***REMOVED***
            return $this->headers;
        }

        public function getQueryParams()
***REMOVED***
            return $this->queryParams;
        }

        public function getPostVars()
***REMOVED***
            return $this->postVars;
        }

        public function getPostFiles()
***REMOVED***
            return $this->postFiles;
        }
    }
