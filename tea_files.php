<?php
include 'config.php';
session_start();

if(empty($_GET['folder_path'])){
  $folder_path = "files";  
  $a_folder_path = "files";
  $cht_path ="最上層";
}else{
  $folder_path = "files/".str_replace("@","/",$_GET['folder_path']);
  $a_folder_path = str_replace("/","@",$folder_path);
  $cht_path="最上層/".str_replace("@","/",$_GET['folder_path']);
}
if($_POST['action']=="upload_file"){
  if ($_FILES['file']['error'] === UPLOAD_ERR_OK){    
    //echo '檔案名稱: ' . $_FILES['file']['name'] . '<br/>';
    //echo '檔案類型: ' . $_FILES['file']['type'] . '<br/>';
    //echo '檔案大小: ' . ($_FILES['file']['size'] / 1024) . ' KB<br/>';
    //echo '暫存名稱: ' . $_FILES['file']['tmp_name'] . '<br/>';
  
    $path = $_POST['path'];
    $folder_path = str_replace("@","/",$path);

    $file = $_FILES['file']['tmp_name'];
    $dest = $folder_path."/".$_FILES['file']['name'];
    
    # 將檔案移至指定位置    
    if (move_uploaded_file($file, $dest)) {
      //echo "文件已成功上傳：" . htmlspecialchars($fileName);
    } else {
        //echo "文件上傳失敗。";
    }
  } else {
    //echo '錯誤代碼：' . $_FILES['my_file']['error'] . '<br/>';
  }
  if($folder_path != "files"){
    $f = str_replace('/','@',$folder_path);
    $f = str_replace('files@','',$f);
    header("Location: tea_files.php?folder_path=".$f);
  }else{
    header("Location: tea_files.php");
  }
  
}

if($_GET['action']=="delete_file"){
  $path_array = explode('@',$_GET['path']);
  unset($path_array[count($path_array) - 1]);
  unset($path_array[0]);
  
  $delete_file = str_replace('@','/',$_GET['path']);
      
  
  if(file_exists($delete_file)){
      unlink($delete_file);
  }
  
  $new_path = "";
  foreach($path_array as $p){
       $new_path = $new_path.'@'.$p;
  }
  $new_path = substr($new_path,1);
  if(empty($new_path)){
    header("Location: tea_files.php");
  }else{
    header("Location: tea_files.php?folder_path=".$new_path);
  }
  
}

if($_POST['action']=="make_dir"){
  $new_dir = str_replace('@','/',$_POST['path'])."/".$_POST['folder'];
  mkdir($new_dir);
  if($_POST['path'] == "files"){
    header("Location: tea_files.php");
  }else{
    $new_path = str_replace("files@","",$_POST['path']);
    header("Location: tea_files.php?folder_path=".$new_path);
  }
}
if($_GET['action']=="delete_dir"){
  $delete_dir = str_replace('@','/',$_GET['path']);
  if(is_dir($delete_dir)){
    deleteDirectory($delete_dir);
  }
  header("Location: tea_files.php");
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
          <li><a href="index.php">作業上傳</a></li>
          <li><a href="tea_files.php" class="active">檔案下載</a></li>       
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
    <!-- About Section -->
    <section id="about" class="about section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <span>檔案下載<br></span>
        <h2>檔案下載</h2>
        <p>電腦老師要給學生下載的檔案</p>
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
          <?php
            if($_SESSION['admin']=="YES"){
              echo "<table class='table table-bordered'>";
              echo "<thead class='table-warning'><tr><th colspan='3'>電腦老師選項</th></tr></thead>";
              echo "<tr><td>".$cht_path."</td><td>動作</td><td>執行</td></tr>";  
              if(!empty($_GET['folder_path'])){               
                $delete_folder="<a href='tea_files.php?action=delete_dir&path={$a_folder_path}' onclick=\"return confirm('確定真的要刪除？連同底下所有檔案喔！');\"><i class='bi-trash text-danger'></i></a>"; 
                echo "<tr><td></td><td>刪除 {$cht_path}</td><td>{$delete_folder}</td></tr>";  
              }
              echo "<tr><td></td><td>";
              echo "
                <form action='tea_files.php' method='post' enctype='multipart/form-data' id='myForm'>
                  <div class='input-group'>                  
                      <input type='file' class='form-control' id='fileInput' name='file' aria-describedby='inputGroupFileAddon04' aria-label='Upload'>
                      <input type='hidden' name='action' value='upload_file'>
                      <input type='hidden' name='path' value='{$a_folder_path}'>
                      <button class='btn btn-outline-secondary' type='button' id='inputGroupFileAddon04' onclick='validateForm();'>上傳檔案放這裡</button>            
                  </div>
                </form>      
              ";
              echo "</td><td></td></tr>";
              echo "<tr><td></td><td>";
              echo "
                <form action='tea_files.php' method='post' enctype='multipart/form-data' id='myForm2'>
                  <div class='input-group mb-3'>
                    <input type='text' class='form-control' id='fileInput2' name='folder' placeholder='請取資料夾名稱' aria-label='Recipients username' aria-describedby='button-addon2' required>
                    <input type='hidden' name='action' value='make_dir'>
                    <input type='hidden' name='path' value='{$a_folder_path}'>
                    <button class='btn btn-outline-secondary' type='button' id='button-addon2' onclick='validateForm2();'>新增新資料夾</button>
                  </div>
                </form>
              ";
              echo "</td><td></td></tr>";
              echo "</table>"; 
            }              
          ?>
                    
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
            function validateForm2(){
              const fileInput = document.getElementById("fileInput2");
              const files = fileInput.value; // 獲取選中的文件列表

              if (files.length === 0) {
                alert("你沒有取資料夾名稱！");                
                return false; // 阻止表單提交
              }

              document.getElementById("myForm2").submit();
            }
          </script>

          <?php
            $d = get_files($folder_path);        
            
            echo "<table class='table table-bordered table-striped'>";
            echo "<thead class='table-secondary'><tr><th colspan='2'>檔案列表</th></tr></thead>";

            foreach($d['folders'] as $k=>$v){
              if(empty($_GET['folder_path'])){
                $path = $v;
              }else{
                $path = str_replace("/","@",$_GET['folder_path'])."@".$v;
              }                            
              echo "<tr><td style='width:60px'><img src='assets/img/folder.svg' width='50px;'></td><td><a href='tea_files.php?folder_path={$path}'>{$v}</a></td></tr>";                                    
            }
            foreach($d['files'] as $k=>$v){
              if(empty($_GET['folder_path'])){
                $path = $v;
              }else{
                $path = str_replace("/","@",$_GET['folder_path'])."@".$v;
              }
              
              if($_SESSION['admin']=="YES"){
                $new_path = str_replace("@","/",$path);                
                $delete="<a href='tea_files.php?action=delete_file&path=files@{$path}'><i class='bi-x-circle text-danger' onclick=\"return confirm('確定真的要刪除？');\"></i></a>";
              }else{                
                $delete= "";
              }
              if($_SESSION['admin']=="YES"){
                $new_path = str_replace("@","/",$path);                
                $delete="----<a href='tea_files.php?action=delete_file&path=files@{$path}'><i class='bi-x-circle text-danger' onclick=\"return confirm('確定真的要刪除？');\"></i></a>";
              }else{                
                $delete= "";
              }
              echo "<tr><td style='width:60px'><img src='assets/img/file.svg' width='50px;'></td><td><a href='file.php?action=download_file&path=files@{$path}' target='_blank'>{$v}</a>{$delete}</td></tr>";                                                  
            }
            echo "</table>";
          ?> 
        </div>        
      </div>

    </section><!-- /About Section -->    
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
