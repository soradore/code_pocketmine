<?php


    $pdo = new PDO('sqlite:../code_pocketmp/my_sqlite_db.db');

    // SQL実行時にもエラーの代わりに例外を投げるように設定
    // (毎回if文を書く必要がなくなる)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // デフォルトのフェッチモードを連想配列形式に設定 
    // (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // テーブル作成
    $pdo->exec("CREATE TABLE code(
        id VARCHAR(10),
        password VARCHAR(50),
        code TEXT
    )");


    $code = "<html>  aaaa </html>";
    $code = htmlspecialchars($code, ENT_QUOTES);
    $stmt = $pdo->prepare("INSERT INTO code(id, code, password) VALUES (?, ?, ?)");
    foreach ([['123', $code, '123']] as $params) {
        $stmt->execute($params);
    }

?>