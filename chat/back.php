<?php 

//Action загрузки сообщений при загрузки страницы ---------------------------------------------------------------------------------------
function user_get_msg() {
	$data = get_chat_items();
	$data['root'] = $_POST["root"];
	echo json_encode($data);
	wp_die();
}

//Action сохранение сообщение в базу и отправка новых на фронт---------------------------------------------------------------------------------------
function user_post_msg() {

	$userID  = $_POST["userID"];
	$userName   = $_POST["userName"];
	$userAvatar   = $_POST["avatar"];
	$message = $_POST['message'];
	$userTo = $_POST['userTo'];

	global $wpdb;
	$tablename = $wpdb->prefix.'chat';
	$wpdb->insert( $tablename, array(
			'userFrom' => $userID,
			'message' => $message,
			'userTo' => $userTo,
	));

	echo json_encode(get_chat_items($userID, $userName, $userAvatar));
	wp_die();
}

//Функция выборки переписки---------------------------------------------------------------------------------------
function get_chat_items($uID = '', $uName = '', $uAvatar = '') {
	global $wpdb;
	$tablename = $wpdb->prefix.'chat';

	
	$userID     = $_POST["userID"] ? $_POST["userID"] : $uID;
	$userName   = $_POST["userName"] ? $_POST["userName"] : $uName;
	$userAvatar   = $_POST["avatar"] ? $_POST["avatar"] : $uAvatar;

	$query = $wpdb->get_results( "SELECT * FROM $tablename WHERE userFrom = $userID OR userTo = $userID;" , ARRAY_A );

	$data['items'] = $query;
	$data['user'] = array('ID' => $userID, 'name' => $userName, 'avatar' => $userAvatar);

	if ($_POST["root"]) {
		foreach ($query as $item) {
			if ($item['userTo'] != $userID) {
				$data['userTo'][] = $item['userTo'];
			}
			if ($item['userFrom'] != $userID) {
				$data['userTo'][] = $item['userFrom'];
			}
		}
		$data['userTo'] = array_map("unserialize", array_unique(array_map("serialize", $data['userTo'])));
	
		foreach($data['userTo'] as $item) {
			
			$userToInfo[] = array(
				'id' => $item,
				'name' => get_user_by('id', $item)->display_name,
				'avatar' => get_avatar_url($item)
			);

		}
		$data['userTo'] = $userToInfo;
	}else{
		$data['userTo'][] = array(
				'id' => 1,
				'name' => get_user_by('id', 1)->display_name,
				'avatar' => get_avatar_url(1)
			);;
	}

	return $data;
}