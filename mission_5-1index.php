<!DOCTYPE html>
<html lang="ja"> 
    <head>
        <meta charset="utf-8">
        <title>mission_5-1</title>
    </head>
    <body>
        <!--送信フォーム-->
        <form action="" method="post"><br><br>
            名前：<input type="text" name="name" value="<?php echo $nameCopy ?>"><br>
            コメント：<input type="text" name="comment" value="<?php echo $commentCopy ?>">
            <!--これは後から隠す-->
            <input type="hidden" name="editMode" value="<?php echo $idCopy ?>"><br>
            <!--ここにパスワード欄を入れる-->
            パスワード: <input type="text" name="passWord">
            <input type="submit"><br><br>
        </form>

        <!--削除フォーム-->
        <form action="" method="post">
            削除対象番号: <input type="text" name="delNum"><br>
            パスワード: <input type="text" name="passDel">
            <input type="submit" value="削除"><br><br>
        </form>

        <!--編集フォーム-->
        <form action="" method="post">
            編集対象番号: <input type="text" name="editNum"><br>
            パスワード: <input type="text" name="passEdit">
            <input type="submit" value="編集"><br><br>
        </form> 
            
    </body>
</html>
