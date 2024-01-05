<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
	<title>送信が完了しました</title>
	<meta name="description" content="">
	<meta name="keywords" content="">


	<!-- favicon -->
	<link rel="icon" href="../favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">

	<link rel="stylesheet" href="../css/reset.css" media="all">
	<link rel="stylesheet" href="../css/pc.css?date=20221123" media="all and (min-width: 900px)">
	<link rel="stylesheet" href="../css/phone.css?date=20221123" media="all and (max-width: 899px)">

</head>




<body>

	<div id="wrapper">

		<main>

			<section class="sub_title">
				<div class="sub_title_wrapper">
					<h2>送信が完了しました</h2>
				</div>
			</section>

			<div class="soushin_page">
				<?php

				function sanitize($before)
				{
					foreach ($before as $key => $value) {
						$after[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
					}
					return $after;
				}

				$post = sanitize($_POST);

				$naiyo = $post['naiyo'];
				$onamae = $post['onamae'];
				$hurigana = $post['hurigana'];
				$kaisya = $post['kaisya'];
				$postal1 = $post['postal1'];
				$postal2 = $post['postal2'];
				$address = $post['address'];
				$tel = $post['tel'];
				$email1 = $post['email1'];
				$otoiawase = $post['otoiawase'];

				if ($naiyo == 'gyoumu') {
					$naiyo = '業務について';
				}
				if ($naiyo == 'saiyo') {
					$naiyo = '採用について';
				}

				print '<p>この度は、---株式会社へお問い合わせいただきありがとうございます。</p>';
				print '<p>3営業日以内に折り返しご連絡させていただきます。</p><br>';
				print '<p>※3営業日以内に弊社より連絡がない場合、メールが正しく送られていない可能性がございます。</p>';
				print '<p>その際はお手数ですが、お電話にてご連絡ください。</p>';

				$honbun = '';
				$honbun .= $onamae . "様\n\nこの度は、---株式会社へお問い合わせいただきありがとうござます。\n";
				$honbun .= "以下のお問い合わせ内容を受け付けました。\n";
				$honbun .= "3営業日以内に折り返しご連絡させていただきますので、しばらくお待ちくださいませ。\n";
				$honbun .= "\n";
				$honbun .= "※3営業日以内に弊社より連絡がない場合、メールが正しく送られていない可能性がございます。\n";
				$honbun .= "その際はお手数ですが、お電話にてご連絡ください。\n";
				$honbun .= "\n";
				$honbun .= "\n";
				$honbun .= "ーーー　お問い合わせ内容　ーーーーーーーーーーー\n";
				$honbun .= "【問合せ内容】\n";
				$honbun .= $naiyo . "\n";
				$honbun .= "【お名前】\n";
				$honbun .= $onamae . "\n";
				$honbun .= "【フリガナ】\n";
				$honbun .= $hurigana . "\n";
				$honbun .= "【会社・団体名】\n";
				$honbun .= $kaisya . "\n";
				$honbun .= "【郵便番号】\n";
				$honbun .= $postal1 . "-" . $postal2 . "\n";
				$honbun .= "【住所】\n";
				$honbun .= $address . "\n";
				$honbun .= "【電話番号】\n";
				$honbun .= $tel . "\n";
				$honbun .= "【メールアドレス】\n";
				$honbun .= $email1 . "\n";
				$honbun .= "【お問い合わせ】\n";
				$honbun .= $otoiawase . "\n";
				$honbun .= "ーーーーーーーーーーーーーーーーーーーーーーーー\n";
				$honbun .= "\n";

				$honbun .= "このメールは---株式会社のホームページからお問い合わせいただいた方へ自動送信しております。\n";
				$honbun .= "お心当たりのない方は、下記へご連絡ください。\n";
				$honbun .= "\n";

				$honbun .= "ーーーーーーーーーーーーーーーーーーーーーーーー\n";
				$honbun .= "---株式会社\n";
				$honbun .= "--県--市--xxx-x\n";
				$honbun .= "TEL xxx-xxx-xxxx\n";
				$honbun .= "MAIL example@example.com\n";
				$honbun .= "ーーーーーーーーーーーーーーーーーーーーーーーー\n";
				//print '<br />';
				//print nl2br($honbun);

				$motourl = $_SERVER['HTTP_REFERER'];



				//お客さん宛自動返信メール
				$title = '【---株式会社】お問い合わせありがとうございます';
				$header = 'From:example@example.com';
				$honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
				mb_language('Japanese');
				mb_internal_encoding('UTF-8');
				if ($motourl == 'https://---.co.jp/contact/form_check.php' && $_POST['csrf_token'] == $_SESSION['csrf_token']) {
					mb_send_mail($email1, $title, $honbun, $header);
				}

				$kigyo = '';
				$kigyo .= "ホームページからお問い合わせがありました。\n";
				$kigyo .= "お問い合わせの内容は以下のとおりです。\n";
				$kigyo .= "受信から3営業日以内にお客様へ連絡してください。\n";
				$kigyo .= "\n";
				$kigyo .= "\n";
				$kigyo .= "ーーー　お問い合わせ内容　ーーーーーーーーーーー\n";
				$kigyo .= "【問合せ内容】\n";
				$kigyo .= $naiyo . "\n";
				$kigyo .= "【お名前】\n";
				$kigyo .= $onamae . "\n";
				$kigyo .= "【フリガナ】\n";
				$kigyo .= $hurigana . "\n";
				$kigyo .= "【会社・団体名】\n";
				$kigyo .= $kaisya . "\n";
				$kigyo .= "【郵便番号】\n";
				$kigyo .= $postal1 . "-" . $postal2 . "\n";
				$kigyo .= "【住所】\n";
				$kigyo .= $address . "\n";
				$kigyo .= "【電話番号】\n";
				$kigyo .= $tel . "\n";
				$kigyo .= "【メールアドレス】\n";
				$kigyo .= $email1 . "\n";
				$kigyo .= "【お問い合わせ】\n";
				$kigyo .= $otoiawase . "\n";
				$kigyo .= "ーーーーーーーーーーーーーーーーーーーーーーーー\n";
				$kigyo .= "\n";
				$kigyo .= "\n";
				$kigyo .= "このメールはお問い合わせフォームから送信されました。\n";


				//企業宛メール
				$title = 'ホームページよりお問い合わせがありました【' . $naiyo . '】';
				$header = 'From:' . $email1;
				$kigyo = html_entity_decode($kigyo, ENT_QUOTES, 'UTF-8');
				mb_language('Japanese');
				mb_internal_encoding('UTF-8');
				if ($motourl == 'https://---.co.jp/contact/form_check.php' && $_POST['csrf_token'] == $_SESSION['csrf_token']) {
					mb_send_mail('example@example.com', $title, $kigyo, $header);
				}

				?>
			</div>


			<p class="toppagelink"><a href="https://---.co.jp">トップページへ</a></p>

		</main>

	</div><!-- wrapper -->

</body>

</html>