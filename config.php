<?
try{
    $pdo = new PDO('mysql:dbname=loftblog_dz2;host=localhost', 'root', '');
    $pdo->query('SET NAMES "UTF-8"');

} catch(PDOException $exc){
    echo "�������� ������ ��� ������ � ��: ".$exc->getMessage();
}
?>