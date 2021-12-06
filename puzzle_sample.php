<?php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/vendor/autoload.php';

// HTTP リクエストを送るためのクライアントを設定

use GuzzleHttp\Client;


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $private_key = "KEY_XXXXXXXXXXXXXX";
    $uri = "https://jp.api.capy.me/";
    $client = new Client(["base_uri" => $uri, "timeout" => 5000, "stream" => false]);
    try {
        $challenge_key = $_POST['capy_challengekey'];  // POSTパラメータから Challenge Key を設定
        $answer = $_POST['capy_answer']; // POSTパラメータから回答データを取得
        $param_puzzle = [
            "form_params" => [
                "capy_privatekey" => $private_key, // Capy Console より PRIVATE KEY を設定
                "capy_challengekey" => $challenge_key, // Challenge Key を設定
                "capy_answer" => $answer // 回答データを送信
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

<?php if (!empty($message)): ?>
    <p><?= $message ?></p>
<?php endif; ?>

<div id='capy-wrapper'>
    <form method="post">
        <input type="text" name="userid" placeholder="ID"><br/>
        <input type="password" name="password" placeholder="パスワード">
        <input type="submit" value="ログイン">
        <?php # JavaScript からパズルCAPTCHAを呼び出し ?>
        <script type="text/javascript"
                src="https://jp.api.capy.me/puzzle/get_js/?k=PUZZLE_XXXXXXXXXXXXX">
        </script>
    </form>
</body>
</html>
