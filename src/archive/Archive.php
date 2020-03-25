<?php
namespace tools\archive;

final class Archive{

    public function __construct()
    {
        if(!extension_loaded('zip')){
            throw new \Exception('The extension zip not install');
        }
    }

    /**
     * 创建一个zip文件,并将整个目录下的所有文件添加到zip文件中
     * @param  $zip_name  string   压缩包文件名称
     * @param  $file      file     文件
     */
    public function addZip($zip_name , $file){
        $zip = new \ZipArchive();
        $res = $zip->open( $zip_name.'.zip',\ZipArchive::CREATE);
        if($res && is_file($file)){ //添加文件
            $zip->addFile($file);
        }else{ //添加空目录
            $zip->addEmptyDir($file);
        }        
        $zip->close();
    }


    /**
     * 读取zip文件内容
     * @param string $filename 待解压文件
     * @param string $path     解压到目录
     */
    function unzip($filename, $path)
    {
        //先判断待解压的文件是否存在
        if (!file_exists($filename)) {
            throw new \Exception('The file is not exist');
        }
        //打开压缩包
        $resource = zip_open($filename);
        //遍历读取压缩包里面的一个个文件
        while ($dir_resource = zip_read($resource)) {
            //如果能打开则继续
            if (zip_entry_open($resource, $dir_resource)) {
                //获取当前项目的名称,即压缩包里面当前对应的文件名
                $name = zip_entry_name($dir_resource);
                //中文文件名称，转码
                $file_name = $path . iconv('utf8', 'gb2312', $name);
                //以最后一个“/”分割,再用字符串截取出路径部分
                $file_path = substr($file_name, 0, strrpos($file_name, "/"));
                //如果路径不存在，则创建一个目录，true表示可以创建多级目录
                if (!is_dir($file_path)) {
                    mkdir($file_path, 0777, true);
                }
                //如果不是目录，则写入文件
                if (!is_dir($file_name)) {
                    //读取这个文件
                    $file_size = zip_entry_filesize($dir_resource);
                    //最大读取6M，如果文件过大，跳过解压，继续下一个
                    if ($file_size < (1024 * 1024 * 30)) {
                        $file_content = zip_entry_read($dir_resource, $file_size);
                        file_put_contents($file_name, $file_content);
                    } else {
                        throw new \Exception('The file size is over 6M');
                    }
                }
                //关闭当前
                zip_entry_close($dir_resource);
            }
        }
        //关闭压缩包
        zip_close($resource);
        return 200;
    }


}