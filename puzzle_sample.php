<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/vendor/autoload.php';

// .envファイルを使用する
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// HTTP リクエストを送るためのクライアントを設定
use GuzzleHttp\Client;

// Capyの死活監視結果取得
function check_capy_status(){
    $is_available = false;
    $fh = fopen("https://sip-operation.capy.me/status/puzzle", "r");
    if ($fh) {
      $line = fgets($fh, 1024);
      $is_available = ($line == "operational");
      fclose($fh);
    }
    return $is_available;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $puzzle_key = $_ENV['PUZZLE_KEY'];      // .envファイルからパズルキーを取得
    $private_key = $_ENV['PRIVATE_KEY'];    // .envファイルからプライベートキーを取得
    $uri = "https://jp.api.capy.me/";
    $client = new Client(["base_uri" => $uri, "timeout" => 5000, "stream" => false]);
    try {
        $challenge_key = $_POST['capy_challengekey'];        // POSTパラメータから Challenge Key を設定
        $answer = $_POST['capy_answer'];                     // POSTパラメータから回答データを取得
        $param_puzzle = [
            "form_params" => [
                "capy_privatekey" => $private_key,          // プライベートキーを設定
                "capy_challengekey" => $challenge_key,      // Challenge Keyを設定
                "capy_answer" => $answer                    // 回答データを設定
            ]];
        $url_puzzle = 'puzzle/verify';
        $response_puzzle = $client->post($url_puzzle, $param_puzzle);
        $data_puzzle = explode("\n", $response_puzzle->getBody());
        echo "Result: " . $data_puzzle[1];
    } catch (Exception $e) {
        echo $e;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Below</title>
</head>
<body>

<div id="capy-wrapper">
    <form method="post">
        <input type="text" name="userid" placeholder="ID"><br/>
        <input type="password" name="password" placeholder="パスワード">
        <input type="submit" value="ログイン">
        <div id="capy-captcha">
            <?php 
                if (check_capy_status()) {  
                    $puzzle_key_escape = htmlspecialchars($puzzle_key, ENT_QUOTES, 'UTF-8');
                    echo "<script type='text/javascript' src='https://jp.api.capy.me/puzzle/get_js/?k={$puzzle_key_escape}'></script>";                    
                }
            ?>
        </div>
    </form>
</body>
</html>