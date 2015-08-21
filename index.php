<?php

// データ挿入用SQLのベース
// このベースにvalue部分を付与するだけでインサートできるよ！
// 注意！ valueはカッコで囲ってから結合してね！
$insert_thread_sql_base = "insert into sureddo (id, title, text, name) value ";
$insert_res_sql_base = "insert into res (id, name, text, thread_id) value ";

// DB接続
$link = mysql_connect('localhost', 'root', '');
if(!$link) {
	echo "DB接続エラー";
	exit;
}

$db_selected = mysql_select_db('test', $link);

if(!$db_selected) {
	echo "DB選択エラー";
	exit;
} 



// ポスト値の取得
$post = !empty($_POST) ? $_POST : "";

// スレッドの投稿があった場合はDBへ挿入
if(!empty($post['sureddo'])) {
	$thread_add_sql = $insert_thread_sql_base."(null, '{$post['title']}', '{$post['text']}', '{$post['name']}')";
	$thread_add = mysql_query($thread_add_sql);
	if(!$thread_add) {
		$error_mes = "error!! thread_add";
	}
}

// レスの投稿があった場合はDBへ挿入
if(!empty($post['res'])) {
	$res_add_sql = $insert_res_sql_base."(null, '{$post['name']}', '{$post['text']}', {$post['thread_id']})";
	$res_add = mysql_query($res_add_sql);
	if(!$res_add) {
		$error_mes = "error!! res_add";
	}
}


// スレッド呼び出し
$thread_arr = mysql_query('select * from `sureddo` order by id desc');
$res_arr = mysql_query('select * from `res` order by id desc');

$data = array();
if($thread_arr !== false) {
	while($thread_data = mysql_fetch_assoc($thread_arr)) {
		$data[$thread_data['id']] = $thread_data;
	}
}
if($res_arr !== false) {
	while($res_data = mysql_fetch_assoc($res_arr)) {
		$data[$res_data['thread_id']]['res'][] = $res_data;
	}
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	</head>
	<?=!empty($error_mes) ? $error_mes : "" ?><br/>

	<form method="post" name="sureddo_form" action="./">
		title:<input type="text" name="title" /><br/>
		text:<textarea name="text" ></textarea><br/>
		name:<input type="text" name="name" />
		<input type="submit" name="sureddo" value="send" />
	</form>



	<?php if(!empty($data)) { ?>
	<ul>
	<?php foreach($data as $thread) { ?>
		<li>
			<table border="1">
				<tr>
					<td><?=$thread['id']?></td>
					<td><?=$thread['name']?></td>
				</tr>
				<tr>
					<td colspan="2"><?=$thread['title']?></td>
				</tr>
				<tr>
					<td colspan="2"><?=$thread['text']?></td>
				</tr>
			</table>
			<?php if(!empty($thread['res'])) { ?>
			<table border="1">
			<?php foreach($thread['res'] as $res) { ?>
				<tr>
					<td><?=$res['id']?></td>
					<td><?=$res['name']?></td>
					<td><?=$res['text']?></td>
				</tr>
			<?php } ?>
			</table>
			<?php } ?>
			<form method="post" name="res_form<?=$thread['id']?>" action="./">
				name:<input type="text" name="name" />
				text:<input type="text" name="text" /><br/>
				<input type="hidden" value="<?=$thread['id']?>" name="thread_id" />
				<input type="submit" name="res" value="send" />
			</form>
		</li>
	<?php } ?>
	</ul>
	<?php } else { ?> 
	<div>
		no thread
	</div>
	<?php } ?>

</html>

<?php 

// select
//$result = mysql_query('select * from test');
//while($row = mysql_fetch_assoc($result)) {
// var_dump($row);
//}
//
//insert
//$sql = "insert into test(id, name, date, deleted) value(1,2,3,4)";
//$result = mysql_query($sql);
//var_dump($result);

// create
/*
CREATE TABLE sureddo
(
id INT(11),
title VARCHE(255),
text TEXT,
name VARCHAR(255)
);
/*

/*
CREATE TABLE res
(
id INT(11),
name VARCHAR(255),
text TEXT,
thread_id INT(11)
);
*/

$insert_thread_sql_base = "insert into sureddo (id, title, text, name) value ";
$insert_res_sql_base = "insert into res (id, name, text, thread_id) value ";

mysql_close($link);
