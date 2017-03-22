<?php
	/* Check Data */
		if(empty($_POST["phone"])) {
			exit();
		}
	/* /Check Data */
	/* SQL */
		/* Connection */
			try {
				$conn = new PDO("mysql:host=localhost;dbname=base", "user", "password");
				$conn->exec("set names utf8");
			}
			catch (PDOException $e) {
				echo "Connection failed: " . $e->getMessage();
			}
		/* /Connection */
		/* Check */
			$query = $conn->prepare("SELECT id FROM feedback WHERE ip = :ip AND DATE(time) = CURDATE()");
			$query->bindParam(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR, 18);
			$query->execute();
			$result = $query->fetchColumn();
		
			if($query->rowCount() > 0)
			{
				echo json_encode(array(
					"status" =>	"Кажется вы сегодня уже оставляли заявку!"
				));
				exit;
			}
		/* /Check */
		/* Add */
			$query = $conn->prepare("INSERT INTO feedback (phone, time, ip) VALUES (:phone, :time, :ip)");
			$query->bindParam(":phone", $_POST["phone"], PDO::PARAM_STR, 18);
			$query->bindParam(":time", date("Y-m-d H:i:s"), PDO::PARAM_STR, 18);
			$query->bindParam(":ip", $_SERVER["REMOTE_ADDR"], PDO::PARAM_STR, 18);
			$query->execute();
			$result = $query->fetchColumn();
			
			if($query->rowCount() > 0)
			{
				$status = "Ваша заявка успешно принята. Мы обязательно с вами свяжемся!";
			}
			else
			{
				$status = "Произошла ошибка. Мы уже работаем над ее решением.";
			}
			
			echo json_encode(array(
				"status" =>	$status
			));
		/* /Add */
	/* /SQL */

	/* Telegram */
		$TelegramKey = "key";
		$TelegramId = "id";
		$TelegramText = "Новая заявка:%0AТелефон: ".$_POST["phone"]."%0AДата/Время: ".date("Y-m-d H:i:s");

			$sendTelegram = file_get_contents("https://api.telegram.org/bot".$TelegramKey."/sendMessage?chat_id=".$TelegramId."&text=".$TelegramText);
	/* /Telegram */
?>