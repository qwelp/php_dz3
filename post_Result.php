<?
try{
    $pdo = new PDO('mysql:dbname=loftblog_dz2;host=localhost', 'root', '');
    $pdo->query('SET NAMES "UTF-8"');

} catch(PDOException $exc){
    echo "Возникла ошибка при работе с БД: ".$exc->getMessage();
}

if(empty($_POST["db"]) || empty($_POST["format"])) {

    echo "Выбирете таблицу и формат данных для экспорта ";
    echo '<a href="export_DB.php">Вернуться назад</a>';
    exit;
}

$table = trim($_POST["db"]);
$sql = "SELECT * FROM `{$table}`";
$result = $pdo->query($sql);
$records = $result->fetchAll(PDO::FETCH_ASSOC);

if($_POST["format"] == "csv") {

    $head = array();
    foreach($records[0] as $k => $val) {
        $head[] = $k;
    }

    $file = $table.".csv";
    $csv = fopen($file, 'w');
    fputcsv($csv, $head, ';');
    foreach ($records as $value) {
        fputcsv($csv, $value, ';');
    }
    fclose($csv);

    header ("Content-Type: application/csv");
    header ("Accept-Ranges: bytes");
    header ("Content-Length: ".filesize($file));
    header ("Content-Disposition: attachment; filename=".$file);
    readfile($file);
    header("Location: export_DB.php");

}

if($_POST["format"] == "json") {

    $arJson = array();

    for ($i = 0, $count = count($records); $i < $count; $i++) {

        $item = array();
        foreach ($records[$i] as $k => $value) {
            $item[$k] =  $value;
        }
        $arJson[] = $item;
    }


    $file = $table.".json";
    file_put_contents($file, json_encode($arJson, JSON_UNESCAPED_UNICODE));
    header ("Content-Type: application/json");
    header ("Accept-Ranges: bytes");
    header ("Content-Length: ".filesize($file));
    header ("Content-Disposition: attachment; filename=".$file);
    readfile($file);
    header("Location: export_DB.php");
}

if($_POST["format"] == "xml") {


    $dom = new DOMDocument('1.0', 'UTF-8');
    $shop = $dom->createElement($table);
    $dom->appendChild($shop);

    $products = $dom->createElement('items');
    $shop->appendChild($products);

    for ($i = 0, $count = count($records); $i < $count; $i++) {

        foreach($records[$i] as $k => $value) {

            if($k == "id") {

                $product = $dom->createElement('item');
                $attr_id = $dom->createAttribute('id');
                $attr_id->value = $value;
                $product->appendChild($attr_id);
            }
            else
            {
                $product_mark = $dom->createElement($k, $value);
                $product->appendChild($product_mark);
            }
        }
        $products->appendChild($product);
    }

    $file = $table.".xml";
    $dom->save($file);
    header ("Content-Type: application/xml");
    header ("Accept-Ranges: bytes");
    header ("Content-Length: ".filesize($file));
    header ("Content-Disposition: attachment; filename=".$file);
    readfile($file);
    header("Location: export_DB.php");

    /*header('Content-Type: text/xml');
    echo $dom->saveXML();*/

}
?>