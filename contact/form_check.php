<?php
session_start();

//  csrf用トークンを生成
$toke_byte = openssl_random_pseudo_bytes(16);
$csrf_token = bin2hex($toke_byte);
//  トークンをセッションに保存
$_SESSION['csrf_token'] = $csrf_token;
?>
<!DOCTYPE html>
<html lang="ja">

<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# article: http://ogp.me/ns/article#">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes">
	<title>お問い合わせ内容確認</title>
	<meta name="description" content="">
	<meta name="keywords" content="">

	<!-- favicon -->
	<link rel="icon" href="../favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="../apple-touch-icon.png">

	<link rel="stylesheet" href="../css/reset.css" media="all">
	<link rel="stylesheet" href="../css/pc.css?date=20221122" media="all and (min-width: 900px)">
	<link rel="stylesheet" href="../css/phone.css?date=20221122" media="all and (max-width: 899px)">

</head>


<body>

	<div id="wrapper">

		<main>

			<section class="sub_title">
				<div class="sub_title_wrapper">
					<h2>入力内容の確認</h2>
				</div>
			</section>


			<section class="kakunin">
				<div class="kakunin_con">

					<?php
					//Recaptcha tokenをpostで受け取る処理を追加 If文にてScore0.5超えで送信処理、それ以下はエラー文表示
					$recaptchaToken = $_POST['recaptchaToken'];
					$recaptcha_secret = '6LdgRH8jAAAAAJAeek-z19VWvyah-jLtrqOSmecB';

					$recaptch_url = 'https://www.google.com/recaptcha/api/siteverify?secret=';
					$recaptcha = file_get_contents(
						$recaptch_url . $recaptcha_secret . '&response=' . $recaptchaToken
					);
					$recaptcha = json_decode($recaptcha);

					//print_r('$recaptcha->score : '.var_export($recaptcha->score,true));
					if ($recaptcha->score >= 0.5) {
						// reCAPTCHA合格

						function sanitize($before)
						{
							foreach ($before as $key => $value) {
								$value = mb_convert_kana($value);
								$value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
								$value = str_replace("\r\n", "\r", $value);
								$value = str_replace("\r", "\n", $value);
								$value = str_replace("\n", "<br>", $value);
								$value = str_replace("\t", " ", $value);
								$value = str_replace(",", "&#44;", $value);
								$value = str_replace("'", "&#039;", $value);

								$key = mb_convert_kana($key);
								$key = htmlspecialchars($key, ENT_QUOTES, 'UTF-8');
								$key = str_replace("\r\n", "\r", $key);
								$key = str_replace("\r", "\n", $key);
								$key = str_replace("\n", "", $key);
								$key = str_replace("\t", "", $key);
								$key = str_replace(",", "、", $key);
								$key = str_replace("'", "’", $key);

								$after[$key] = $value;
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
						$email2 = $post['email2'];
						$otoiawase = $post['otoiawase'];
						$security = $post['security'];

						$okflg = true;
					} else {
						$naiyo = NULL;
						$onamae = NULL;
						$hurigana = NULL;
						$kaisya = NULL;
						$postal1 = NULL;
						$postal2 = NULL;
						$address = NULL;
						$tel = NULL;
						$email1 = NULL;
						$email2 = NULL;
						$otoiawase = NULL;
						$security = NULL;

						print '<p style="margin:50px 0; text-align:center;">不正なアクセスです。</p>';
						$okflg = false;
					}

					?>
					<table>
						<tr>
							<th>お問い合わせ内容</th>
							<td><?php
									if ($naiyo == '') {
										print '<span class="errormessage">お問い合わせ内容を選択してください。</span>';
										$okflg = false;
									} else {
										if ($naiyo == 'gyoumu') {
											print '業務について';
										}
										if ($naiyo == 'saiyo') {
											print '採用について';
										}
									} ?></td>
						</tr>
						<tr>
							<th>お名前</th>
							<td><?php
									if ($onamae == '') {
										print '<span class="errormessage">お名前が入力されていません。</span>';
										$okflg = false;
									} else {
										print $onamae;
									} ?></td>
						</tr>
						<tr>
							<th>フリガナ</th>
							<td><?php
									if ($hurigana == '') {
										print '<span class="errormessage">フリガナが入力されていません。</span>';
										$okflg = false;
									} else {
										print $hurigana;
									} ?></td>
						</tr>
						<tr>
							<th>会社・団体名</th>
							<td><?php
									if ($kaisya !== '') {
										print $kaisya;
									} ?></td>
						</tr>
						<tr>
							<th>郵便番号</th>
							<td><?php
									if ($postal1 !== '' && $postal2 !== '') {
										if (preg_match('/\A[0-9]+\z/', $postal1) == 0 || preg_match('/\A[0-9]+\z/', $postal2) == 0) {
											print '<span class="errormessage">郵便番号は半角数字で入力してください。</span>';
											$okflg = false;
										} else {
											print $postal1;
											print '-';
											print $postal2;
										}
									} elseif ($postal1 !== '' || $postal2 !== '') {
										print '<span class="errormessage">郵便番号を正確に入力してください。</span>';
										$okflg = false;
									}
									?>
							</td>
						</tr>
						<tr>
							<th>住所</th>
							<td><?php
									if ($address !== '') {
										print $address;
									} ?></td>
						</tr>
						<tr>
							<th>電話番号</th>
							<td><?php
									if (preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $tel) == 0) {
										print '<span class="errormessage">電話番号を正確に入力してください。</span>';
										$okflg = false;
									} else {
										print $tel;
									} ?></td>
						</tr>
						<tr>
							<th>メールアドレス</th>
							<td><?php
									if (preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/', $email1) == 0) {
										print '<span class="errormessage">メールアドレスを正確に入力してください。</span>';
										$okflg = false;
									} else {
										if ($email1 != $email2) {
											print '<span class="errormessage">メールアドレスが一致しません。</span>';
											$okflg = false;
										} else {
											print $email1;
										}
									} ?></td>
						</tr>
						<tr>
							<th>お問い合わせ</th>
							<td><?php
									if ($otoiawase == '') {
										print '<span class="errormessage">お問い合わせが入力されていません。</span>';
										$okflg = false;
									} else {
										print $otoiawase;
									} ?></td>
						</tr>
					</table>

					<?php

					if ($security !== '') {
						$okflg = false;
					}

					if ($okflg == true) {
						print '<p class="form_kakunin">入力された内容でよろしければ【送信】をクリックしてください。</p>';
						print '<form method="post" action="form_done.php">';
						print '<input type="hidden" name="naiyo" value="' . $naiyo . '">';
						print '<input type="hidden" name="onamae" value="' . $onamae . '">';
						print '<input type="hidden" name="hurigana" value="' . $hurigana . '">';
						print '<input type="hidden" name="kaisya" value="' . $kaisya . '">';
						print '<input type="hidden" name="postal1" value="' . $postal1 . '">';
						print '<input type="hidden" name="postal2" value="' . $postal2 . '">';
						print '<input type="hidden" name="address" value="' . $address . '">';
						print '<input type="hidden" name="tel" value="' . $tel . '">';
						print '<input type="hidden" name="email1" value="' . $email1 . '">';
						print '<input type="hidden" name="otoiawase" value="' . $otoiawase . '">';
						print '<input type="hidden" name="csrf_token" value="' . $csrf_token . '">';
						print '<input type="button" onclick="history.back()" value="戻る" class="kakuninbtn_modoru">';
						print '<input type="submit" value="送信" class="kakuninbtn_soushin">';
						print '</form>';
					} else {
						print '<p class="form_kakunin">入力された内容に不備があります。<br>前のページに戻って、入力内容をご確認ください。</p>';
						print '<form>';
						print '<input type="button" onclick="history.back()" value="戻る" class="kakuninbtn_modoru">';
						print '</form>';
					}
					?>




				</div><!-- kakunin_con -->
			</section><!-- kakunin -->

		</main>

	</div><!-- wrapper -->

	<script type="text/javascript">
		//送信ボタンを押した際に送信ボタンを無効化する（連打による多数送信回避）
		$(function() {
			$('[type="submit"]').click(function() {
				$(this).prop('disabled', true); //ボタンを無効化する
				$(this).closest('form').submit(); //フォームを送信する
			});
		});
	</script>

</body>

</html>