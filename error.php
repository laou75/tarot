<html><body>
<h1>Erreur</h1>
<?php
if ($_GET['err'])
    echo base64_decode($_GET['err']);
else
    echo 'erreur inconnue';
?>
</body></html>
