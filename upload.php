<?php
$post = $_POST;
$file = $_FILES;
$file_name = $post['file_name'];
$blob_num = $post['blob_num'];
$total_blob_num = $post['total_blob_num'];
var_dump($post);
var_dump($file);
// 存储地址
$path = 'slice/'.date('Ymd')  ;
if (!is_dir($path)) {
    mkdir($path, 0777, true);
}
$filename = $path .'/'. $file_name . '_' . $blob_num;
$realPath = $file['file']['tmp_name'];
//上传
$upload = file_put_contents($filename, file_get_contents($realPath));

//判断是否是最后一块，如果是则进行文件合成并且删除文件块
if($blob_num == $total_blob_num){
    for($i=1; $i<= $total_blob_num; $i++){
        $blob = file_get_contents($path.'/'. $file_name.'_'.$i);
        file_put_contents($path .'/'. $file_name,$blob,FILE_APPEND);

    }
    //合并完删除文件块
    for($i=1; $i<= $total_blob_num; $i++){
        unlink($path.'/'. $file_name.'_'.$i);
    }
}

if ($upload){
    return 1;
}else{
    return 0;
}
