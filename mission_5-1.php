<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission_5-1</title>
    </head>
    <body>
 
        <?php
        
        //データベース接続
        $dsn='データベース名';
        $user='ユーザー名';
        $password='パスワード';
        $pdo = new PDO($dsn,$user,$password,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        
            //テーブル作成
	    $sql = "CREATE TABLE IF NOT EXISTS mission5"
	    ." ("
	    . "id INT AUTO_INCREMENT PRIMARY KEY,"
	    . "name char(32),"
	    . "comment TEXT,"
        . "date TEXT,"
        . "pass char(10)"
	    .");";
	    $stmt = $pdo->query($sql);

        
        $name = $_POST["fname"];
        $str = $_POST["fstr"];            
        $pass = $_POST["fpass1"];
        $delete = $_POST["delete"];
        $edit = $_POST["edit"];
        $id = $_POST["id"];
        //投稿用プログラム
        if(!empty($name) && !empty($str) && !empty($pass)){
            //パスワード設定の有無
            if(empty($id)){
            echo "接続成功<br>";
            $sql = $pdo -> prepare("INSERT INTO mission5 
            (name, comment, date ,pass) VALUES (:name, :comment, :date, :pass)");
            $sql -> bindParam(':name',$name, PDO::PARAM_STR);
            $sql -> bindParam(':comment',$str, PDO::PARAM_STR);
            $sql -> bindParam(':date',$date, PDO::PARAM_STR);
            $sql -> bindParam(':pass',$pass, PDO::PARAM_STR);
            $date = date("Y/m/d H:i:s");
            $sql -> execute();
            echo "投稿成功<br>";
            }else{
	        $sql = 'UPDATE mission5 SET name=:name,comment=:comment,
	        pass=:pass,date=:date WHERE id=:id';
	        $stmt = $pdo->prepare($sql);
	        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
	        $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
	        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	        $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
	        $date = date("Y/m/d H:i:s");
	        $stmt->execute();
            }
        }
        //削除用プログラム
        elseif(!empty($delete)){
            	$id = $delete;
	            $sql = 'delete from mission5 where id=:id';
	            $stmt = $pdo->prepare($sql);
	            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt->execute();
	            echo "削除完了<br>";
        }
        //編集用の抽出
        elseif(!empty($edit)){
            $id = $edit;
            $sql = 'SELECT * FROM mission5 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();      // ←SQLを実行する。
            $results = $stmt->fetchAll();
            //↓がうまくいっていないようだ
	            foreach ($results as $row){
		            //編集IDの抽出
		            $editid = $row['id'];
		            $editname = $row['name'];
		            $editstr = $row['comment'];
	                $editpass = $row['pass'];
	                echo "抽出完了<br>";
                }
        }
        else{
            echo "入力してください<br>";
        }
        //表示用プログラム 
        $sql2 = 'SELECT * FROM mission5';
	    $stmt2 = $pdo->query($sql2);
	    $results = $stmt2->fetchAll();
	    foreach ($results as $row){
		    //表示したいもの
		    echo $row['id'].',';
		    echo $row['name'].',';
		    echo $row['comment'].',';
		    echo $row['date'].',';
		    //echo $row['pass'].'<br>';
	    echo "<hr>";
	    }
	    ?>
	    <form action"" method ="post">
          <!--入力フォーム作成 
          value は編集用--> 
          投稿<br>
            <input type = "hidden" name = "id"
            placeholder = "表示用"
            value = <?php echo $editid;?>>
            <input type = "text" name = "fname"
            placeholder = "名前を入力" 
            style = "width: 175px;"
            value = <?php echo  $editname;?>>           
            <!--送信ボタン-->
            <input type = "submit" name = "submit"><br>
            <input type = "text" name = "fstr"
            placeholder = "コメントを入力してね"
            style = "width: 175px; height: 50px;" 
            value = <?php echo $editstr;?>
             ><br>
            <input type = "text" name = "fpass1"
            placeholder = "パスワードを決めてね"
            style = "width: 175px;"
            value = <?php echo $editpass;?>><br>

        </form>
        <form action"" method ="post">
            <!--削除用ボタン-->
            削除<br>
            <input type ="number" name= "delete" 
            placeholder= "番号"
            style = "width: 50px;"> 
            <input type ="text" name= "delpass" 
            placeholder= "パスワード">
            <input type ="submit" value= "削除" ><br>
        </form>
        <form action"" method ="post">
            <!--編集用ボタン-->
            編集<br>
            <input type ="number" name = "edit" 
            placeholder = "番号"
            style = "width: 50px;">
            <input type ="text" name ="edpass"
            placeholder ="パスワード">
            <input type ="submit" value = "編集">
        </form>
    </body>
</html>