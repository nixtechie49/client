<?php

declare(strict_types=1);

/*
 * This file is part of the IOTA PHP package.
 *
 * (c) Benjamin Ansbach <benjaminansbach@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IOTA\RemoteApi;

use IOTA\Node;

/**
 * Interface FactoryInterface.
 *
 * Simple factory interface.
 */
interface ActionFactoryInterface
{
    public function factory(Node $node);
}
