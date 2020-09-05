<?php

//DB接続設定
$dsn='データベース名';
$user='ユーザー名';
$password='パスワード';
$pdo=new PDO($dsn,$user,$password,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	."("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
    . "comment TEXT,"
    . "date TEXT,"
    . "passWord TEXT"
	.");";
$stmt = $pdo->query($sql);

//上のテーブルは、drop文で元のを削除後、新たに作ったもの


//送信フォームに関する処理
if(!empty($_POST["name"]) && !empty($_POST["comment"])) {
    
    //新規投稿時の処理
    if(empty($_POST["editMode"]) && !empty($_POST["passWord"])) {
    
        //データレコードの挿入
        $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment, date, passWord) VALUES (:name, :comment, :date, :passWord)");
        //各値の固定（？）
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':passWord', $passWord, PDO::PARAM_STR);
        
        //好きな名前、好きな言葉は自分で決めること
        $name = $_POST["name"];
        $comment = $_POST["comment"]; 
        $date = date("Y年m月d日 H:i:s");
        $passWord = $_POST["passWord"];

        $sql -> execute();
    }

    //編集モード時の処理
    if(!empty($_POST["editMode"])) {
        
        $id = $_POST["editMode"]; //変更する投稿番号
        $name = $_POST["name"];
        $comment = $_POST["comment"];
        $date = date("Y年m月d日 H:i:s");
        
        //指定のデータレコードの内容を編集
        $sql = 'UPDATE tbtest SET name=:name,comment=:comment,date=:date WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
    }
}


//削除フォームに関する処理
if(!empty($_POST["delNum"]) && !empty($_POST["passDel"])) {
    
    $id = $_POST["delNum"] ; // idがこの値のデータだけを抽出したい、とする
    
    //ここで、当該レコードの各カラムの値を配列として取り出す
    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); 
    $stmt->execute();                             
    $results = $stmt->fetchAll();
    
    //$passWordを定義        
    foreach($results as $row) {
        $passWord=$row['passWord'];
    }
        
    if($_POST["passDel"]==$passWord) {
        //入力したデータレコードを削除する
        //$id = $_POST["delNum"];
        $sql = 'delete from tbtest where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        echo "パスワードが違います";
    }
}

//valueの値の定義
$nameCopy="";
$commentCopy="";
$idCopy="";

//編集フォームに関する処理
if(!empty($_POST["editNum"]) && !empty($_POST["passEdit"])) {
    
    $id = $_POST["editNum"]; // idがこの値のデータだけを抽出したい、とする

    //ここで、当該レコードの各カラムの値を配列として取得する
    $sql = 'SELECT * FROM tbtest WHERE id=:id';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
            
    //$passWordを定義
    foreach($results as $row) {
        $passWord=$row['passWord'];
    }
        
    //パスワードが正しい場合、当該idのname・comment・idカラムの値を各送信フォームに表示させる
    //それ以外の時は、valueには空の値を設定
    if($_POST["passEdit"]==$passWord) {
        //nameとcommentカラムの値を取得
        foreach($results as $row) {
            $nameCopy=$row["name"];
            $commentCopy=$row["comment"];
            $idCopy=$row["id"];
        } 
    } else {
        echo "パスワードが違います";
    }
}
//値の確認
echo $nameCopy. "<br>";
echo $commentCopy. "<br>";
echo $idCopy;


//HTMLフォームは別ファイルへ
include("./mission_5-1index.php");


//入力したデータレコードを抽出して表示する
$sql = 'SELECT * FROM tbtest';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].' ';
	echo $row['name'].' ';
    echo $row['comment'].' ';
    echo $row['date']. "<br>";
    //echo $row['passWord'].'<br>';
}
?>