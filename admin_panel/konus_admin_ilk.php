 <?php 

 include '../vt/vt_baglantisi.php';
 session_start();

 $kontrol_admin = mysqli_query($baglanti, "SELECT * FROM `login_admin`");
 $kontrol_sonuc_admin = mysqli_fetch_array($kontrol_admin);

$vt_ip_admin = $kontrol_sonuc_admin['admin_ip'];  // veri tabanindan aldigim ip degeri 
$vt_brovser_admin = $kontrol_sonuc_admin['admin_browser'];  // veri tabanindan aldigim brovser  

if (isset($_SESSION['Ip']) && isset($_SESSION['browser'])) {
	$session_admin_ip=$_SESSION['Ip'];
	$session_admin_browser=$_SESSION['browser'];

	if ($session_admin_ip==$vt_ip_admin && $session_admin_browser==$vt_brovser_admin) { ?>


		<?php  
		include '../vt/vt_baglantisi.php';

		if (isset($_GET['sinif'])) {
			$sinif=$_GET['sinif'];
		}
		$dersler_admin = $baglanti->query("SELECT * FROM `dersler` WHERE ders_sinif='$sinif'");

		?>

		<!DOCTYPE html>
		<html>
		<head>
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1"> 
			<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
			<!--pencere resmı-->
			<link rel="icon" type="image/png" href="../resimler/logo.png">
			<!--font awesome den icon kullanmak için link-->
			<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
			<title>Konuşanlar</title>
		</head>
		<body>
			<?php 
			include './includes_admin/header_admin.php';
			?>
			<section style="margin-top:50px;">
				<div class="container">
					<div class="row">
						<div class="col-lg-4 col-md-12"></div> 
						<div class="col-lg-4 col-md-12">
							<ul class="list-group">
								<li class="list-group-item active" aria-current="true">DERSLER</li>
								<?php while ($dersler_get = $dersler_admin->fetch_assoc()) {    ?>
									<a style="text-decoration:none;" href="konus_admin_son.php?ders_id=<?php echo $dersler_get['id']; ?> "><li class="list-group-item"><?php echo $dersler_get['ders_ad']; ?></li> </a>
									
								<?php } ?>
							</ul>
						</div>
						<div class="col-lg-4 col-md-12"></div> 
					</div>
				</div>
			</section>
			

			<!-- LINKLER KISMI-->

			<!--Bootstrap linki -->
			<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
			<!--sweet eklentısı -->
			<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
			<!--jquery eklentısı -->
			<script src="https://code.jquery.com/jquery-3.6.1.js" integrity="sha256-3zlB5s2uwoUzrXK3BT7AX3FyvojsraNFxCc2vC/7pNI=" crossorigin="anonymous"></script>

			<script type="text/javascript">
				$(function() {

					//modal ac ve verı yansıt kısmı burda
					$('#guncelleme_modal').hide();
					$(document).on('click', '#login_guncel', function(e) { 
						e.preventDefault(); 
						var guncell_id = $(this).attr('loginGun');  
						$('#guncelleme_modal').show();
						$('#asil').hide(); 
						$.post("function_admin/admin_function.php", {
							"guncell_id": guncell_id,
							"guncel_veri_see": "guncel_veri_see"
						}).done(function(data) { 
							var obj = jQuery.parseJSON(data);   
							$('#hadi_gun').attr('veri_gun_id',obj.id); 
							$('#gun_ad').val(obj.name);  
							$('#gun_soy').val(obj.surname);  
							$('#gun_okul').val(obj.okul_no);  
							$('#gun_em').val(obj.email);   
						});
					});


					// modal kapa islemi
					$(document).on('click', '#modal_kapa', function() {
						$('#asil').show();
						$('#guncelleme_modal').hide();
						window.location.reload();
					});


					// login ogrencı sıstemı silme islemi yapıldı
					$(document).on('click', '#login_sil', function(e) {
						var login_sil_id = $(this).attr('login');
						e.preventDefault();
						$.post("function_admin/admin_function.php", {
							"login_sil_id": login_sil_id,
							"login_silis": "login_silis"
						}).done(function(data) {
							if (data == "yes") {
								Swal.fire({
									position: 'top-end',
									icon: 'success',
									title: 'Silme İşlemi Yapıldı',
									showConfirmButton: false,
									timer: 1500
								}); 
							} else {
								Swal.fire('Bir Sıkıntı Oluştu');
							} 
						});
					});

					// login guncelleme ıslemı
					$(document).on('click', '#hadi_gun', function(e) {

						var guncelle_veri = $(this).attr('veri_gun_id'); 
						var gun_ad=$('#gun_ad').val();
						var gun_soy=$('#gun_soy').val();
						var gun_okul=$('#gun_okul').val();
						var gun_em=$('#gun_em').val(); 
						e.preventDefault(); 

						$.post("function_admin/admin_function.php", {
							"guncelle_veri": guncelle_veri,
							"gun_ad": gun_ad,
							"gun_soy": gun_soy,
							"gun_okul": gun_okul,
							"gun_em": gun_em,
							"guncellenecek": "guncellenecek"
						}).done(function(data) {
							if (data == "yes") {
								alert("Güncelleme İşlemi Başarılı");
								$('#asil').show();
								$('#guncelleme_modal').hide();
								window.location.reload();
							} else {
								alert("olmadı malesef");
							} 
						}); 
					});

					$('#log_modal').hide();
					$(document).on('click','#ekle_login',function(e){
						$('#log_modal').show();
						$('#asil').hide(); 
					});



					$(document).on('click','#log_gun',function(e){  
						var log_ad=$('#log_ad').val();
						var log_so=$('#log_so').val();
						var log_okul=$('#log_okul').val();
						var log_em=$('#log_em').val();  

						$.post("function_admin/admin_function.php", {
							"log_ad": log_ad,
							"log_so": log_so,
							"log_okul": log_okul,
							"log_em": log_em, 
							"log_ekle": "log_ekle"
						}).done(function(data) {
							if (data == "yes") {
								alert("Login Ekleme İşlemi Başarılı");
								$('#asil').show();
								$('#guncelleme_modal').hide();
								$('#log_modal').hide();
								window.location.reload();
							} else {
								alert("olmadı malesef");
							} 
						}); 
					});






				});
			</script>
		</body>
		</html>


		<?php
	} else {
		include '../includes/logine_gitmeli.php';
		header("Refresh: 2; url=../login.php");
	} 
} else {
	include '../includes/logine_gitmeli.php';
	header("Refresh: 2; url=../login.php");
}


?>

