<?php

declare(strict_types=1);

/**
 * This file is part of the Carbon package.
 *
 * (c) Brian Nesbitt <brian@nesbot.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Carbon\PHPStan;

if (!class_exists(LazyMacro::class, false)) {
    abstract class LazyMacro extends AbstractReflectionMacro
    {
        /**
         * {@inheritdoc}
         */
        public function getFileName(): ?string
***REMOVED***
            $file = $this->reflectionFunction->getFileName();

            return (($file ? realpath($file) : null) ?: $file) ?: null;
        }

        /**
         * {@inheritdoc}
         */
        public function getStartLine(): ?int
***REMOVED***
            return $this->reflectionFunction->getStartLine();
        }

        /**
         * {@inheritdoc}
         */
        public function getEndLine(): ?int
***REMOVED***
            return $this->reflectionFunction->getEndLine();
        }
    }
}
