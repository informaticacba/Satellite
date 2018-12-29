<?php

mkdir('app');
mkdir('app/Controller');
file_put_contents('app/Controller/HelloController.php',
'<?php

namespace App\Controller;

use Satellite\Response;

class HelloController
{
    public function hello(Request $request)
    {
        $data = array(
            \'now\' => date(\'Y-m-d H:i:s\'),
            \'userAgent\' => $request->getUserAgent(),
        );
        $response = new Response();
        return $response->view(\'/hello.php\', $data);
    }
}
');

mkdir('database');
mkdir('database/migrations');
mkdir('database/sessions');

mkdir('bootstraps');
file_put_contents('bootstraps/bootstrap.php',
'<?php

use Satellite\App;
use Satellite\Router;

return function()
{
    App::setLayers(array(
        \'\Satellite\Router\',
    ));
    Router::set(\'GET\', \'/\', \'\App\Controller\HelloController\', \'hello\');
};
');

mkdir('public');
file_put_contents('public/.htaccess','
Options +SymLinksIfOwnerMatch

<IfModule mod_rewrite.c>
  RewriteEngine on
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteRule . /index.php [L]
</IfModule>
');
file_put_contents('public/index.php',
'<?php

define(\'APP_ROOT\', preg_replace(\'/\/public$/\', \'\', __DIR__));

require_once(APP_ROOT . \'/vendor/autoload.php\');

Satellite\App::init(APP_ROOT . \'/initialize\');
Satellite\App::handle(new Satellite\Request())->send();
');

mkdir('public/js');
mkdir('public/css');
mkdir('public/img');

mkdir('resources');
mkdir('resources/views');
file_put_contents('resources/views/hello.php',
'<h3>Hello Satellite world!</h3>
<h4><?php echo $now ?></h4>
<h5><?php echo $userAgent ?></h5>
');
