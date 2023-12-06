<?php
/** @var $token frontend\controllers\FactorController */
?>
<form method="post" action="https://ikc.shaparak.ir/iuiv3/IPG/Index/" enctype="‫‪multipart/form-data‬‬" onload="document.getElementById('pay').click()">
    <input type="hidden" name="tokenIdentity" value="<?= $token ?>">
    <input type="submit" value="pay" hidden="hidden" id="pay">
</form>