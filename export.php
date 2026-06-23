<?php include 'config.php'; ?>
<?php
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=riwayat.xls");
$q=$conn->query("SELECT * FROM riwayat");
while($d=$q->fetch_assoc()){
 echo "$d[user]\t$d[hasil]\t$d[tanggal]\n";
}
?>