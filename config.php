<?php
$admin="tea";
$pass="1234";


function get_files($folder){
        $files = [];
        if (is_dir($folder)) {
            if ($handle = opendir($folder)) { //開啟現在的資料夾 
                while (false !== ($file = readdir($handle))) {
                    //避免搜尋到的資料夾名稱是false,像是0
                    if ($file != "." && $file != ".." && $file != ".DS_Store") {
                        //去除掉..跟.
                        array_push($files, $file);
                    }
                }
                closedir($handle);
            }
        }
        sort($files);
        $i = 0;
        $j = 0;
        foreach($files as $file){
            if(is_dir("./".$folder."/".$file)){                
                $d['folders'][$i] = $file;
                $i++;
            }else{
                $d['files'][$j] = $file;
                $j++;
            }
            
        }
        return $d;    
}

function deleteDirectory($dir) {
    // 檢查目錄是否存在
    if (!is_dir($dir)) {
        return false; // 如果目錄不存在，直接返回
    }

    // 開啟目錄並讀取內容
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue; // 跳過特殊目錄 . 和 ..
        }

        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_dir($path)) {
            // 遞迴刪除子目錄
            deleteDirectory($path);
        } else {
            // 刪除文件
            unlink($path);
        }
    }

    // 刪除當前目錄
    return rmdir($dir);
}

function createZipAndDownload($folderPath, $zipFileName) {
    // 檢查目錄是否存在
    if (!is_dir($folderPath)) {
        die("目錄不存在！");
    }

    // 創建 ZIP 檔案對象
    $zip = new ZipArchive();

    // 定義臨時 ZIP 文件的路徑
    $zipFilePath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipFileName;

    // 開啟 ZIP 檔案，如果無法創建則退出
    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("無法創建 ZIP 檔案！");
    }

    // 遞迴將目錄內容加入 ZIP 檔案
    $folderPath = realpath($folderPath); // 獲取絕對路徑
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($files as $file) {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($folderPath) + 1); // 計算相對路徑

        if ($file->isDir()) {
            // 如果是目錄，則在 ZIP 中創建一個資料夾
            $zip->addEmptyDir($relativePath);
        } else {
            // 如果是文件，則加入 ZIP 中
            $zip->addFile($filePath, $relativePath);
        }
    }

    // 關閉 ZIP 檔案
    $zip->close();

    // 設置下載頭
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    header('Content-Length: ' . filesize($zipFilePath));

    // 輸出 ZIP 檔案
    readfile($zipFilePath);

    // 刪除臨時文件
    unlink($zipFilePath);

    exit();
}

function download_file($file){
    if (file_exists($file)) {
        // 設定標頭，強制下載檔案
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
    
        // 清除輸出緩衝區
        flush();
    
        // 讀取檔案並輸出
        readfile($file);
        exit;
    } else {
        echo "檔案不存在！";
    }
}