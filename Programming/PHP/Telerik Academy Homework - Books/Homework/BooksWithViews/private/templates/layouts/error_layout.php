<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?= $data['title']; ?></title>
    </head>
    <body>
        <header>
            <div>
                <h1>
                    <?= $data['title']; ?>
                </h1>
            </div>
            <nav>
                <?php
                require $data['nav'];
                ?>
            </nav>
            <div>
                <?php
                require $data['login'];
                ?>
            </div>
        </header>
        <article>
            <h1>OOPS! Yet another pesky error!</h1>
            <p>An error has occured. Sorry for the inconvinience! Just curse and go back!</p>
            <?php
            require $data['error_message'];
            ?>
        </article>
    </body>
</html>