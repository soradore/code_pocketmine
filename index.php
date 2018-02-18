<!DOCTYPE html>
<html lang='ja'>
<head>
 <title>コード共有</title>
 <meta charset='utf-8'>

 <!-- jQuery -->
 <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

 <!-- syntax hilight -->

 <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.0/ace.js"></script>
</head>
<body>
<?php


if(!empty($_GET['id']) && isset($_GET['id'])){

    try{
    $pdo = new PDO('sqlite:my_sqlite_db.db');

    // SQL実行時にもエラーの代わりに例外を投げるように設定
    // (毎回if文を書く必要がなくなる)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // デフォルトのフェッチモードを連想配列形式に設定 
    // (毎回PDO::FETCH_ASSOCを指定する必要が無くなる)
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // テーブル作成

    $stmt = $pdo->prepare("SELECT * FROM code WHERE id = :id");
    $stmt->bindValue(':id', $_GET['id'], PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    $code = html_entity_decode($result['code']);

    echo <<< EOM
    <div id="code" style="height: 600px; width: 80%"></div>
    <script>
      var editor = ace.edit("code");
      editor.setTheme("ace/theme/eclipse");
      editor.setFontSize(14);
      editor.getSession().setMode("ace/mode/php");
      editor.getSession().setUseWrapMode(true);
      editor.getSession().setTabSize(4);
      editor.setValue("{$code}");
    </script>
    <form action='./remove.php' method='post'>
     <input type='password' name='pass' placeholder='password'>
     <input type="hidden" value="{$_GET['id']}">
     <input type='submit' value='削除'>
    </form>
EOM;

    } catch (Exception $e){
        echo $e->getMessage();
    }
}else{

     echo <<< EOM
    <div id="code" style="height: 600px; width: 80%"></div>
    <input type="password" id="pass" placeholder="削除用password">
    <input type="submit" value="送信" id="send">
    <script>
      var editor = ace.edit("code");
      editor.setTheme("ace/theme/eclipse");
      editor.setFontSize(14);
      editor.getSession().setMode("ace/mode/php");
      editor.getSession().setTabSize(4);
    </script>
    <script>
      $(document).ready(function() {

  //送信ボタンをクリック
  $('#send').click(function(){

    //POSTメソッドで送るデータを定義する
    //var data = {パラメータ : 値};
    var code = editor.getValue();
    var pass = $('#pass').val();
    if(code.length == 0){ alert('コードが未記入です'); return;}
    if((pass.length == 0) || (pass == "")){ alert('パスワードが未記入です'); return;}
    
    var data = {
                'code' : code, 
                'pass' : pass
                };

    //Ajax通信メソッド
    //type : HTTP通信の種類(POSTとかGETとか)
    //url  : リクエスト送信先のURL
    //data : サーバに送信する値
    $.ajax({
      type: "POST",
      url: "add.php",
      data: data,
      //Ajax通信が成功した場合に呼び出されるメソッド
      success: function(data, dataType){
        //デバッグ用 アラートとコンソール
        //alert('送信しました');
        //location.replace('./?id='+data);
        alert(data);
      },
      //Ajax通信が失敗した場合に呼び出されるメソッド
      error: function(XMLHttpRequest, textStatus, errorThrown){
        alert('Error : ' + errorThrown);
      }
    });
    return false;
  });
});

    </script>
EOM;
}
?>
</body>
</html>