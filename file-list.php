<?php
function dump($arr) {
?><pre><?print_r($arr)?></pre><?
}

if(isset($_POST["edit_file"])) {
    file_put_contents($_POST["edit_file"], $_POST["edit_text"]);
    //header("Location: file-list.php");
}
if(isset($_POST["add_name"])) {
    file_put_contents("files/".$_POST["add_name"], $_POST["text"]);
    header("Location: file-list.php");
}
if(isset($_GET["delete"])) {
    @unlink("files/".$_GET["delete"]);
    header("Location: file-list.php");
}


function filesList($dir, $removeFile) // Путь к директории, Массив запрещеных файлов
{
    $html = '';
    if (is_dir($dir)) {

        if ($dh = opendir($dir))
        {
            $html .= '<table>'. PHP_EOL;
            $i = 0;
            while (($file = readdir($dh)) !== false) {

                if (!in_array($file, $removeFile)) {

                    $html .= "<tr>". PHP_EOL;
                    $html .= "<td>".++$i."</td>". PHP_EOL;
                    $html .= "<td><a href=\"?view={$file}\">{$file}</a></td>". PHP_EOL;
                    $html .= "<td><a href=\"?edit={$file}\">Редактировать</a></td>". PHP_EOL;
                    $html .= "<td><a href=\"?delete={$file}\">Удалить</a></td>". PHP_EOL;
                    $html .= "</tr>". PHP_EOL;
                }

            }
            $html .= '<table>'. PHP_EOL;
            closedir($dh);
        }

        echo $html;

        return;


    }
    else
    {
        echo "Ошибка";
        return;
    }
}
?><!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <style>
        table {
            border-collapse: collapse;
        }
        td {
            padding: 5px;
            border: 1px solid #999;
        }
    </style>
</head>
<body>
<?if(isset($_GET["add"])):?>
    <form method="post" action="">
        <table>
            <tr>
                <td>
                    Название файла
                </td>
                <td>
                    <input type="text" name="add_name">
                </td>
            </tr>
            <tr>
                <td>
                    Содержимаое файла
                </td>
                <td><textarea name="text" cols="30" rows="10"><?=$text?></textarea></td>
            </tr>
            <tr>
                <td></td><td><input type="submit" value="Сохранить"></td>
            </tr>
        </table>
        <p><a href="file-list.php">Вернуться к списку</a></p>
    </form>
<?elseif(isset($_GET["view"])):?>
    <?
        $fileName = $_GET["view"];
        $text = file_get_contents("files/".$fileName);
        echo $text;
    ?>
    <p><a href="file-list.php">Вернуться к списку</a></p>
<?elseif(isset($_GET["edit"])):?>
    <?
        $fileName = $_GET["edit"];
        $text = file_get_contents("files/".$fileName);
    ?>
    <form method="post" action="">
        <table>
            <tr>
                <td>
                    <textarea name="edit_text" cols="30" rows="10"><?=$text?></textarea>
                    <input type="hidden" name="edit_file" value="files/<?=$fileName?>">
                </td>
            </tr>
            <tr>
                <td><input type="submit" value="Сохранить"></td>
            </tr>
        </table>
        <p><a href="file-list.php">Вернуться к списку</a></p>
    </form>
<?else:?>
    <p><a href="?add">Добавить файл</a></p>
    <?filesList("files/", array('.', '..'));?>
<?endif;?>

</body>
</html>