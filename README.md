# HttpClient

#### 介绍
轻量级 http client

#### 目录结构
```
├─src                   代码目录
│  ├─Client.php           Http Client     
│  ├─ClientRequest.php    Request类
│  └─ClientResponse.php   Response类
│ 
├─tests                   测试代码   
├─composer.json           composer.json
├─phpunit.xml.dist        测试配置
└─README.md               README.md 
```

#### 安装教程
```
composer require --prefer-dist anxu/http-client "*"
```

#### 使用说明
```php
$client = new Client([
            'Content-type' => 'application/json' //The global header
        ]);

        $response = $client->get('http://xxxxx.com', [
            'key' => 'value' ////The url parameters
        ],[
            'key'=>'value' //Set request http headers
        ]);

//        $response = $client->post('http://xxxxx.com', [
//            'key' => 'value'   //The url parameters
//        ],json_encode([  ////Set http body
//            'id'=>1,
//            'name'=>'test'
//        ]), [
//            'key'=>'value' //Set request http headers
//        ]);

        //Response status
        var_dump($response->getStatus());
        //200

        //Response Headers
        var_dump($response->getHeaders());
//        array(2) {
//            ["Set-Cookie"]=> array(2) {
//                [0]=> string(8) "SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e",
//                [1]=> string(8) "id=941e"
//            }
//            ["Content-Type"]=> array(1) {
//                [0]=> string(9) "text/html"
//            }
//        }

        //The specified response header
        var_dump($response->getHeader('Set-Cookie'));
        //string(8) "SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e"

        var_dump($response->getHeader('Set-Cookie', false));
//       array(2) {
//           [0]=> string(8) "SESSIONID=d98aa7fe2dd4a8b0111374f84ca3941e",
//           [1]=> string(8) "id=941e"
//       }

        //Response body
        echo $response->getContent();
```