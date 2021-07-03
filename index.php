<?php
namespace test;

require_once 'DatabaseConnect.php';

$databaseConnect = new DatabaseConnect();
$mysqli = $databaseConnect->connect();

$sql = "SELECT * FROM posts";

$res = $mysqli->query($sql);

$posts = [];

while($data = $res->fetch_object()) {
    $posts[] = $data;
}

$mysqli->close();


if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $databaseConnect = new DatabaseConnect();
    $mysqli = $databaseConnect->connect();

    $name = $_POST['name'];
    $title = $_POST['title'];
    $detail = $_POST['detail'];

    // プリペアドステートメント
    $stmt = $mysqli->prepare("INSERT INTO posts (name, title, detail) VALUES (?, ?, ?)");

    // 変数のバインド
    $stmt->bind_param('sss', $name, $title, $detail);

    $stmt->execute();

    $mysqli->close();

    header('Location: /');
}
?>

<!DOCTYPE html>
<html lang="JA">
  <head>
    <meta charset="UTF-8">
    <title>ブログサービス</title>
    <style>
      body {
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
<!--  ここでDBから取得した投稿情報の一個目の記事の投稿者名を表示してます。  -->
    <?= $posts[0]->name ?>
    <form action="index.php" method="POST">
        <label for="name">
            氏名:
            <input id="name" type="text" name="name">
        </label>
        <label for="title">
            タイトル:
            <input id="title" type="text" name="title">
        </label>
        <label for="detail">
            本文:
            <input id="detail" type="text" name="detail">
        </label>
        <input type="submit" value="Submit">
    </form>
  </body>
</html>