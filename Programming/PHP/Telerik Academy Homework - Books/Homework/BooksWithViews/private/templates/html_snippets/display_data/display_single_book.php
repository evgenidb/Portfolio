<section>
    <div>
        <label>Заглавие:</label>
        <p><?= $data['book']['title']; ?></p>
    </div>
    <div>
        <label for="book_authors">Автори:</label>
        <ul>
            <?php
            $authors_array = [];
            foreach ($data['book']['authors'] as $author_id => $name) {
                ?>
                <li>
                    <a href="<?= $data['link']['author'] . '&id=' . $author_id; ?>"><?= $name ?></a>
                </li>
                <?php
            }
            ?>
        </ul>

    </div>
</section>