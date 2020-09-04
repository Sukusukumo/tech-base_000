<?php

	// 【サンプル】
	// ・データベース名：tb220381db
	// ・ユーザー名：tb-220381
	// ・パスワード：cK5g9L56Sk
	// の学生の場合：

	// DB接続設定
	$dsn = 'mysql:dbname=**********;host=localhost';
	$user = '**-******';
	$password = '*******';
    $pdo = new PDO($dsn, $user, $password,  //new PDO  PDO接続を呼び出す
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブル作成
    $sql = "CREATE TABLE IF NOT EXISTS tbtest_123"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "created DATETIME,"
	. "password char(20)"
	.");";
	$stmt = $pdo->query($sql);
	//↑$stmt不要？
    
    //送信ボタンを押したときの操作
  
    if(!empty($_POST["nyu_submit"])){
        if(empty($_POST["comment"])){
        echo "コメントが入力されていません<br>";
        }
        if(empty($_POST["name"])){
        echo "名前が入力されていません<br>";
        }
        if(empty($_POST["pass"])){
        echo "パスワードが入力されていません<br>";}
        }
    if(!empty($_POST["comment"]) && !empty($_POST["name"]) && !empty($_POST["pass"])){
	    //新規入力(データを入力)
	    $mien = $_POST["mienai"];
	    if(empty($_POST["mienai"])){
        $sql = $pdo -> prepare("INSERT INTO tbtest_123 (name, comment,created,password)
        VALUES (:name, :comment,:created,:password)");
	    $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	    $sql -> bindParam(':comment', $text, PDO::PARAM_STR);
	    $sql -> bindParam(':created',$created,PDO::PARAM_STR);
	    $sql -> bindParam(':password',$password,PDO::PARAM_STR);
	    $created = date('Y-m-d H:i:s');
        $password = $_POST["pass"];
        $name = $_POST["name"];
	    $text = $_POST["comment"]; 
	    $sql -> execute(); //実行する
	    //編集なら
	     }else{	
	            $id = $mien; //変更する投稿番号
	            $name = $_POST["name"];
	            $text = $_POST["comment"]; 
	            $created = date("Y-m-d H:i:s");
	            $password = $_POST["pass"];
	            //変更する投稿番号が同じなら実行
	            $sql = 'UPDATE tbtest_123 SET name=:name,comment=:comment,
	            password=:password,created=:created WHERE id=:id';
		        $stmt = $pdo -> prepare($sql);
	            $stmt -> bindParam(':name', $name, PDO::PARAM_STR);
	            $stmt -> bindParam(':comment', $text, PDO::PARAM_STR);
	            $stmt -> bindParam(':password',$password, PDO::PARAM_STR);
	            $stmt -> bindParam(':created',$created, PDO::PARAM_STR);
	            $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	            $stmt -> execute();//実行する
	            }
        
    }

        


       
    
    //削除ボタンを押したときの操作
    if(!empty($_POST["saku_submit"])){
        if(empty($_POST["num"])){
            echo "削除番号が入力されていません<br>";
        }else{
        $passs = $_POST["passs"];
        $sql = 'SELECT * FROM tbtest_123'; //テーブルから全部のデータを取る。
	    $stmt = $pdo -> query($sql); //SQL文を実際のデータベースに問い合わせる
	    $results = $stmt -> fetchAll(); //すべての結果行を含む配列を返す
	        foreach ($results as $row){
                if($passs == $row['password'] ){
                    $id = $_POST["num"];
	                $sql = 'delete from tbtest_123 where id=:id';
	                $stmt = $pdo -> prepare($sql); 
	                $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
	                $stmt -> execute(); //実行
		//$rowの中にはテーブルのカラム名が入る
	            }else{
	                echo "パスワードが一致しません<BR>";
	                break;
            }   
        }
    }
    }

 
    
    //編集ボタンを押したときの操作
    if(!empty($_POST["hen_submit"])){
    if(!empty($_POST["numb"]) ){
        $passss= $_POST["passss"];
        $sql = 'SELECT * FROM tbtest_123'; //テーブルから全部のデータを取る。
	    $stmt = $pdo -> query($sql); //SQL文を実際のデータベースに問い合わせる
	    $results = $stmt -> fetchAll(); //すべての結果行を含む配列を返す
	    foreach ($results as $row){
	        if($row['password'] == $passss){
	            $hensyu = $_POST["numb"];
	            if($row['id'] == $hensyu){
	            $namae= $row['name'];
	            $come = $row['comment'];
	            }
	        }else{ 
	            echo "パスワードが一致しません<br>";
	            break;
	        }
	   }
    }else{echo "編集番号が入力されていません<BR>";}
    }
    
    
    //表示
    $sql = 'SELECT * FROM tbtest_123'; //テーブルから全部のデータを取る。
	$stmt = $pdo -> query($sql); //SQL文を実際のデータベースに問い合わせる
    $results = $stmt -> fetchAll(); //すべての結果行を含む配列を返す
	foreach ($results as $row){
		     //$rowの中にはテーブルのカラム名が入る
		         echo $row['id'].',';
		         echo $row['name'].',';
		         echo $row['comment'].',';
		         echo $row['created'].'<br>';
	             echo "<hr>";//水平の線
	}
    
	   

    ?>
    
    <html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
</head>
<body>
    <form action="" method="post">
    名前：<input type="text" name="name" value=
    "<?php if(isset($namae)){echo $namae;}?>">
    コメント：<input type="text" name="comment" value=
    "<?php if(isset($come)){echo $come;}?>">
    <input type="hidden" name="mienai" value=
    "<?php if(isset($hensyu)){echo $hensyu;}?>">
    パスワード：<input type="text" name="pass">
        <input type="submit" name="nyu_submit">
    </form>
    <br>
    <form action="" method="post">
    削除番号指定用フォーム: <input type="number" name="num">
    パスワード：<input type="text" name="passs">
    <input type ="submit" name="saku_submit" value="削除">
    </form>
    <br>
    <form action="" method="post">
    編集番号指定用フォーム:<input type="number" name="numb">
    パスワード：<input type="text" name="passss">
    <input type = "submit" name="hen_submit" value="編集">
    <br>
    
</body>
</html>