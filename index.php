<?php
include 'config.php';
session_start();

if(empty($_GET['folder_path'])){
  $folder_path = "uploads";
  $a_folder_path = "uploads";
  $cht_path ="最上層";
}else{
  $folder_path = "uploads/".str_replace("@","/",$_GET['folder_path']);
  $a_folder_path = str_replace("/","@",$folder_path);
  $cht_path="最上層/".str_replace("@","/",$_GET['folder_path']);
}
if(!empty($_POST)){
  if ($_FILES['file']['error'] === UPLOAD_ERR_OK){
    //echo '檔案名稱: ' . $_FILES['file']['name'] . '<br/>';
    //echo '檔案類型: ' . $_FILES['file']['type'] . '<br/>';
    //echo '檔案大小: ' . ($_FILES['file']['size'] / 1024) . ' KB<br/>';
    //echo '暫存名稱: ' . $_FILES['file']['tmp_name'] . '<br/>';
  
    $path = $_POST['path'];
    $folder_path = "uploads/".str_replace("@","/",$path);

    //檢查是否有相同檔名
    $check = get_files($folder_path);    
    if(in_array($_FILES['file']['name'],$check['files'])){
      echo "此資料夾內已有相同檔名，請回上頁重新一次！<br><p>此頁面將在 <span id=\"countdown\">5</span> 秒後自動返回上一頁...</p>";
      echo "
          <script>
              window.onload = function() {
                  let countdown = 5; // 倒數秒數
                  const countdownElement = document.getElementById(\"countdown\");

                  const interval = setInterval(function() {
                      countdown--;
                      countdownElement.textContent = countdown;

                      if (countdown <= 0) {
                          clearInterval(interval); // 停止倒數
                          window.history.back(); // 返回上一頁
                      }
                  }, 1000); // 每秒執行一次
              };
          </script>
      
      ";
      die();
    }else{
      $file = $_FILES['file']['tmp_name'];
      $dest = $folder_path."/".$_FILES['file']['name'];
      
      # 將檔案移至指定位置    
      if (move_uploaded_file($file, $dest)) {
        //echo "文件已成功上傳：" . htmlspecialchars($fileName);
      } else {
          //echo "文件上傳失敗。";
      }
    }    
  } else {
    //echo '錯誤代碼：' . $_FILES['my_file']['error'] . '<br/>';
  }
  $f = str_replace('/','@',$folder_path);
  $f = str_replace('uploads@','',$f);
  header("Location: index.php?folder_path=".$f);

}

?>
<!DOCTYPE html>
<html lang="zh-TW">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>和東國小電腦課</title>
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">

  <!-- =======================================================
  * Template Name: eNno
  * Template URL: https://bootstrapmade.com/enno-free-simple-bootstrap-template/
  * Updated: Aug 07 2024 with Bootstrap v5.3.3
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="index.php" class="logo d-flex align-items-center me-auto">
        <!-- Uncomment the line below if you also wish to use an image logo -->
        <!-- <img src="assets/img/logo.png" alt=""> -->
        <h1 class="sitename">和東國小電腦課
          <?php          
          if($_SESSION['admin'] == "YES"){
            echo "<i class='bi bi-person-circle'></i>教師版";
          }          
          ?>
        </h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="index.php" class="active">作業上傳</a></li>
          <li><a href="tea_files.php">檔案下載</a></li>       
          <?php
            if($_SESSION['admin'] != "YES"){
              echo "<li><a href='login_form.php'>老師登入</a></li>";
            }else{
              echo "<li><a href='logout.php'>登出</a></li>";
            }
          ?>    
          
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

    </div>
  </header>

  <main class="main">

    <section id="hero" class="featured-services section">
      <div class="container section-title" data-aos="fade-up">
        <span>作業上傳<br></span>
        <h2>作業上傳</h2>
        <p>學生要交作業給電腦老師</p>
      </div><!-- End Section Title -->
      <div class="container">

        <div class="row gy-4">
          <div class="input-group">
              <h3>目前所在資料夾：<span style="color:red"><?php echo $cht_path; ?> <i class='bi bi-folder2-open'></i></span></h3>              
          </div>
          <?php
            if(!empty($_GET['folder_path'])){
              echo "<a class='btn btn-secondary btn-sm' style='width:100px;margin-right:5px' onclick='window.history.go(-1);'><i class='bi bi-skip-start'></i> 回上頁</a>";
            }
          ?>          
          <a class="btn btn-success btn-sm" style="width:100px;margin-right:5px" onclick="location.reload();"><i class="bi bi-arrow-counterclockwise"></i> 重整</a>              
          <form action="index.php" method="post" enctype="multipart/form-data" id="myForm">
            <div class="input-group">                  
                <input type="file" class="form-control" id="fileInput" name="file" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                <input type="hidden" name="path" value="<?php echo $_GET['folder_path']; ?>">
                <button class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04" onclick="validateForm();">我要交作業放這裡</button>            
            </div>
          </form>
          <script>
            function validateForm(){
              const fileInput = document.getElementById("fileInput");
              const files = fileInput.files; // 獲取選中的文件列表

              if (files.length === 0) {
                alert("你沒有選擇檔案要上傳！");                
                return false; // 阻止表單提交
              }

              document.getElementById("myForm").submit();
            }
          </script>
          <?php          
            if($_SESSION['admin']=="YES"){        
              $new_folder_path = str_replace('/','@',$folder_path);
              $download_folder="<a href='dir.php?action=download&path={$new_folder_path}'><i class='bi-download text-success'></i></a>";
              $delete_folder="<a href='dir.php?action=delete&path={$new_folder_path}' onclick=\"return confirm('確定真的要刪除？連同底下所有檔案喔！');\"><i class='bi-trash text-danger'></i></a>";
              echo "<div class='row' style='margin-top:30px;'>";
              echo "<form action='dir.php' method='post'>";
              echo "<table class='table table-bordered'>";
              echo "<thead class='table-warning'><tr><th colspan='3'>電腦老師選項</th></tr></thead>";
              echo "<tr><td rowspan='4' style='width: 300px;'>".$cht_path."</td><td>動作</td><td style='width: 100px;'>執行</td></tr>";    
              if(!empty($_GET['folder_path'])){
                echo "<tr><td>下載 {$cht_path}</td><td>{$download_folder}</td></tr>";
                echo "<tr><td>刪除 {$cht_path}</td><td>{$delete_folder}</td></tr>";  
              }                                                  
              echo "<tr><td><input type='text' name='folder' class='form-control' placeholder='請取資料夾名稱' required></td><td><button class='btn btn-primary'>新增</button></td></tr>";                            
              echo "</table>";
              echo "<input type='hidden' name='action' value='make_dir'>";
              echo "<input type='hidden' name='path' value='{$new_folder_path}'>";
              echo "</form>";
              echo "</div>";
            }               
          ?>        
          <?php
            $d = get_files($folder_path);            
            foreach($d['folders'] as $k=>$v){
              if(empty($_GET['folder_path'])){
                $path = $v;
              }else{
                $path = str_replace("/","@",$_GET['folder_path'])."@".$v;
              }              

              echo "
                <div class='col-lg-2 col-md-3' data-aos='fade-up' data-aos-delay='100'>
                  <div class='service-item position-relative'>
                    <div class='icon'>
                      <i class='bi bi-folder'></i>
                    </div>
                    <a href='index.php?folder_path={$path}' class='stretched-link'>
                      <h3 style='word-break: break-all;'>{$v}</h3>
                    </a>                         
                    <p>資料夾</p>                
                  </div>
                </div>";              
            }
            foreach($d['files'] as $k=>$v){
              if(empty($_GET['folder_path'])){
                $path = $v;
              }else{
                $path = str_replace("/","@",$_GET['folder_path'])."@".$v;
              }

              if($_SESSION['admin']=="YES"){
                $new_path = str_replace("@","/",$path);
                $download="<a href='uploads/{$new_path}' target='_blank'><i class='bi-cloud-download text-success'></i></a>";
                $delete="<a href='file.php?action=delete_file&path=uploads@{$path}'><i class='bi-x-circle text-danger' onclick=\"return confirm('確定真的要刪除？');\"></i></a>";
              }else{
                $download = "";
                $delete= "";
              }
              echo "
                <div class='col-lg-2 col-md-3' data-aos='fade-up' data-aos-delay='100'>
                  <div class='service-item position-relative'>
                    <div class='icon'>
                      <i class='bi bi-file-earmark-arrow-up'></i>
                    </div>                    
                      <h3 style='word-break: break-all;'>{$v} {$download}</h3>                    
                    <p>檔案 {$delete}</p>       
                  </div>
                </div>";              
            }
          ?>                            
        </div>

      </div>

    </section><!-- /Featured Services Section -->
  </main>

  <footer id="footer" class="footer">    

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">eNno</strong> <span>All Rights Reserved</span></p>
      <div class="credits">
        <!-- All the links in the footer should remain intact. -->
        <!-- You can delete the links only if you've purchased the pro version. -->
        <!-- Licensing information: https://bootstrapmade.com/license/ -->
        <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
        Designed by <a href="https://bootstrapmade.com/" target="_blank">BootstrapMade</a> Distributed by <a href="https://themewagon.com" target="_blank">ThemeWagon</a>
        <br>程式設計 by ET Wang    
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>
