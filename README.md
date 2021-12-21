Capy Puzzle CAPTCHA サンプルプログラム
====================================

## 事前準備

### 必要なライブラリのインストール
`composer.json`があるディレクトリにて以下コマンドを実行し、必要なライブラリをインストールします。 

```shell
$ composer insatall
```

上記コマンドが完了すると、`composer.lock`ファイルと、`vendor`ディレクトリが設置されます。

### 環境変数を使用するための準備
環境変数を`.env`ファイルにて定義し、プログラム内で使用します。

`.env`ファイルを使用するためのライブラリを以下コマンドで導入します。

```bash
$ composer require vlucas/phpdotenv
```

`.gitignore`ファイルに、`.env`ファイルを追記します。
今回の`.gitignore`ファイルの中身は以下のようになっています。

```text:.gitignore
/vendor/
.idea/
.env
```

`.env`ファイルを、プロジェクトのルートに作成します。　

```bash
$ touch .env
```

`.env`ファイルにて以下の環境変数を定義します。
hogeには御社のパズルキーを入れてください。
パズルキーは、CAPYコンソールの「ホーム > パズルCAPTCHA > JavaScript」から確認することができます。
`<script src="https://jp.api.capy.me/puzzle/get_js/?k=PUZZLE_hoge"></script>` の 「hoge」を確認してください。
fugaには御社のPrivateキーを入れてください。
Privateキーは、CAPYコンソールの「ホーム > アカウント情報」から確認することができます。

```text:.env
PUZZLE_KEY="PUZZLE_hoge"
PRIVATE_KEY="KEY_fuga"
```

`puzzle_sample.php` をHTTPからPOSTで送信すると、パズルCAPTCHAが埋め込まれたログインページが表示されます。