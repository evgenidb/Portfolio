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
            <?php
            require $data['content'];
            ?>
        </article>
        <section>
            <?php
            require $data['add_comment'];
            ?>
        </section>
        <section>
            <?php
            require $data['comments'];
            ?>
        </section>
    </body>
</html>