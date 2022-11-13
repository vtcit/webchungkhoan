<?php
    $url = isset($_GET['url'])? htmlentities($_GET['url']) : null;
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.jwplayer.com/libraries/q0pNfRzg.js"></script>
    <script>jwplayer.key="XMz8XgbL";</script>
    <title>Embed video</title>
</head>
<body>
    <form action="">
        <input type="text" name="url" value="<?= $url ?>" />
        <button type="submit">GO!</button>
    </form>
    <?php if($url) { ?>
    <div id="player">Loading the player...</div>
    <script>
        // Setup the player
        const player = jwplayer('player').setup({
            file: '<?= $url ?>'
        });

        // Listen to an event
        player.on('pause', (event) => {
            alert('Why did my user pause their video instead of watching it?');
        });

        // Call the API
        const bumpIt = () => {
            const vol = player.getVolume();
            player.setVolume(vol + 10);
        }
        bumpIt();
    </script>
<?php } ?>
</body>
</html>