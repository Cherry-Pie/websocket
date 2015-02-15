## Simple websocket server

Fork of [morozovsk/websocket](https://github.com/morozovsk/websocket) for Laravel 4 integration.

### Installation
Add to app/config/app.php:
```php
'providers' => array(
//...
    'Yaro\Socket\SocketServiceProvider',
//...
),
'aliases' => array(
//...
    'Socket' => 'Yaro\Socket\Facades\Socket',
//...
),
```

### Usage
Sample command:

```php
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ChatCommand extends Command 
{
    protected $name = 'socket:chat';

    protected $description = "chat command";

    public function fire()
    {
        Socket::init($this->argument('action'), array(
            'class' => 'ChatWebsocketDaemonHandler',
            'pid' => '/tmp/websocket_chat.pid',
            'websocket' => 'tcp://127.0.0.1:8000',
            //'localsocket' => 'tcp://127.0.0.1:8010',
            //'master' => 'tcp://127.0.0.1:8020',
            //'eventDriver' => 'event'
        ));
    } // end fire
    
    protected function getArguments()
    {
        return array(
            array('action', InputArgument::REQUIRED, 'start|stop|restart'),
        );
    } // end getArguments
    
    protected function getOptions()
    {
        return array();
    } // end getOptions
}
```


Sample handler class:
```php
class ChatWebsocketDaemonHandler extends WebsocketDaemon
{
    protected function onOpen($connectionId) 
    {
    }

    protected function onClose($connectionId) 
    {
    }

    protected function onMessage($connectionId, $data, $type) {
        if (!strlen($data)) {
            return;
        }

        $message = 'user #'. $connectionId .' ('. $this->pid .'): '. strip_tags($data);

        foreach ($this->clients as $idClient => $client) {
            $this->sendToClient($idClient, $message);
        }
    }
}
```


And run your command (command from sample example, use your own naming):
```shell
php artisan socket:chat
```


And your js on front will be like this:
```javascript
var ws = new WebSocket("ws://127.0.0.1:8000/");
ws.onopen = function() { 
    console.log('socket: open');
};
ws.onclose = function() { 
    console.log('socket: close');
};
ws.onmessage = function(evt) { 
    console.log(evt.data);
};
```
