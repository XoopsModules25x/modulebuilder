<?php declare(strict_types=1);

//.atoum.php

use mageekguy\atoum;
use mageekguy\atoum\reports;

$coveralls = new reports\asynchronous\coveralls('src', 'x3U0mpkL7A9PgaGSUa3tDl3qU9D6wo67y');
$coveralls->addWriter();
$runner->addReport($coveralls);

$script->addDefaultReport();
