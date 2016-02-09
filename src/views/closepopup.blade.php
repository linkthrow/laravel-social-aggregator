<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Everywon</title>

</head>
<body>
<script type="text/javascript">

    var $b = document.getElementsByTagName("body")[0];
    $b.onload = bodyOnLoad;


    /*
     -------------------------------------------------------------- */

    function bodyOnLoad() {
        window.opener.twitterAuthCallback({ 'id' : {{ $id }}, 'username': {{ $username }} });
    }

</script>

</body>
</html>
