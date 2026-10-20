<?php /** @noinspection DisallowWritingIntoStaticPropertiesInspection */

use Kint\Kint;
use Kint\Renderer\RichRenderer;

const KINT_SKIP_HELPERS = true;

define( 'WP_DISABLE_FATAL_ERROR_HANDLER', 'production' !== wp_get_environment_type() );

require __DIR__ . '/includes/vendor/autoload.php';

Kint::$file_link_format = 'editor://open/?file=%f&line=%l';
Kint::$expanded         = true;
Kint::$aliases[]        = 'd';
Kint::$aliases[]        = 'dd';
Kint::$aliases[]        = 's';
Kint::$aliases[]        = 'sd';
Kint::$aliases[]        = 'st';
Kint::$aliases[]        = 'std';
RichRenderer::$theme    = 'aante-light.css';
