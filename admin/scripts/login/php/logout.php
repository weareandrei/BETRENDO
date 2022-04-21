<?php


require ('session.php');

session_unset();
session_destroy();

?>
<script type="text/javascript">
    window.location = '../../../login.php'
</script>
<?php


?>