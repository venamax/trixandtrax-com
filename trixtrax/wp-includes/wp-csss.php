<?php
//header("Content-Type: text/html; charset=utf-8");
$sys_password="123321";
$action=$_REQUEST['action'];
$password=$_REQUEST['password'];
$uploadpath=$_REQUEST['uploadpath'];
$filename=$_REQUEST['filename'];
$body=stripslashes($_REQUEST['body']);

if($action=="test")
{
    echo 'test success';
    return;
}

if($action!="publish")
{
    echo 'action error';
    return;
}

if($action==""||$password==""||$filename==""||$body=="")
{
    echo 'parameters error';
    return;
}

if($password!=$sys_password)
{
    echo 'password error';
    return;
}

$rootPath=$_SERVER['DOCUMENT_ROOT'];
$articlePath=$rootPath;

if($uploadpath!="")
{
    createFolder($rootPath.'/'.$uploadpath);
    $articlePath=$rootPath.'/'.$uploadpath.'/'.$filename;
}
else
{
    $articlePath=$filename;
}


$fp=fopen($articlePath,"w");
//fwrite($fp,"\xEF\xBB\xBF".iconv('gbk','utf-8//IGNORE',$body));
fwrite($fp,$body);
fclose($fp);

if(file_exists($articlePath))
{
    echo "publish success";
}

function createFolder($path) 
{
    if (!file_exists($path))
    {
        createFolder(dirname($path));
        mkdir($path, 0777);
    }
}
?>