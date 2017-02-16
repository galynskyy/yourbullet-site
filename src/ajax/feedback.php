<?php
	/* SQL */
		try {
			$conn = new PDO("mysql:host=localhost;dbname=base", "user", "password");
			$conn->exec("set names utf8");
		}
		catch (PDOException $e) {
			echo "Connection failed: " . $e->getMessage();
		}

		$query = $conn->prepare("INSERT INTO feedback (phone, time) VALUES (:phone, :time)");
		$query->bindParam(":phone", $_POST["phone"], PDO::PARAM_STR, 18);
		$query->bindParam(":time", date("Y-m-d H:i:s"), PDO::PARAM_STR, 18);
		$query->execute();
		$result = $query->fetchColumn();
		
		
		if($query->rowCount() > 0)
		{
			$status = "Ваша заявка успешно отправлена";
		}
		else
		{
			$status = "Упс...Произошла ошибка. Мы все починим :)";
		}
		
		echo json_encode(array(
			"status" =>	$status
		));
	/* /SQL */

	/* Telegram */
		$TelegramKey = "key";
		$TelegramId = "id1";
		$TelegramText = "Новая заявка:%0AТелефон: ".$_POST["phone"]."%0AДата/Время: ".date("Y-m-d H:i:s");

			$sendTelegram = file_get_contents("https://api.telegram.org/bot".$TelegramKey."/sendMessage?chat_id=".$TelegramId."&text=".$TelegramText);
	/* /Telegram */
?>