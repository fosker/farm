<pre>
<?
var_dump([$_REQUEST, $_POST, $_GET, $_FILES]);
?>

    </pre>


<form action="test.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="holong" value="ho long1" />
    <input type="file" name="Banner[image]" />
    <input type="submit" />
</form>
