<?php
    $url = isset($_GET['url'])? htmlentities($_GET['url']) : null;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jwplayer.com/libraries/q0pNfRzg.js"></script>
    <script>jwplayer.key="XMz8XgbL1";</script>
    <title>Embed video</title>
</head>
<body>
    <form action="">
        <input type="text" name="url" value="<?= $url ?>" />
        <button type="submit">GO!</button>
    </form>
    <iframe width="100%" height="100%" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" src="<?= $url ?>" frameborder="0" allowfullscreen=""></iframe>
    <?php if($url) { ?>
    <div id="player">Loading the player...</div>
    <script type="text/javascript">
        var playerInstance = jwplayer("player");
        playerInstance.setup({
        file: "<?= $url ?>",
        width: 640,
        height: 360
        });
    </script>
<?php } ?>
</body>
</html>