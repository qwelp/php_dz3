<?
require_once "config.php";

$sqlTable = "SHOW TABLES";
$resultTable = $pdo->query($sqlTable);
$recordsTable = $resultTable->fetchAll(PDO::FETCH_NUM);

unset($pdo);

?><!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Export_DB</title>
</head>
<body>
<form method="post" action="post_Result.php">
    <p>

        <select name="db">
            <option value="">Выбирете таблицу</option>
            <?
            foreach($recordsTable as $k => $table) {
                echo "<option value=\"{$table[0]}\">{$table[0]}</option>";
            }
            ?>
        </select>
    </p>
    <p>
        <select name="format">
            <option value="">Выбирете формат</option>
            <option value="csv">csv</option>
            <option value="json">json</option>
            <option value="xml">xml</option>
        </select>
    </p>
    <input type="submit" value="выгрузить">
</form>
</body>
</html>
