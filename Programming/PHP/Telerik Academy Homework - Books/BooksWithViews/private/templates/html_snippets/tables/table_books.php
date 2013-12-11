<table class="books">
    <caption>
        Книги
    </caption>
    <thead>
        <tr>
            <th>Заглавие</th>
            <th>Автори</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data['books'] as $book_id => $book) {
            ?>
            <tr>
                <td>
                    <a href="<?= $data['link']['book'] . '&id=' . $book_id; ?>">
                        <?= $book['title']; ?>
                    </a>
                </td>
                <td>
                    <?php
                    $authors = [];
                    foreach ($book['authors'] as $author_id => $name) {
                        $authors[] =
                                '<a href="' . $data['link']['author'] . '&id=' . $author_id . '">' . $name . '</a>';
                    }
                    echo implode(', ', $authors);
                    ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>