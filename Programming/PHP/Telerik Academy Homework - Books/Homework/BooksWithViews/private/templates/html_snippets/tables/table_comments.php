<table class="books">
    <caption>
        Коментари
    </caption>
    <thead>
        <tr>
            <th>Дата</th>
            <th>Потребител</th>
            <th>Съобщение</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data['book']['comments'] as $timestamp => $comment) {
            ?>
            <tr>
                <td>
                    <?= $timestamp; ?>
                </td>
                <td>
                    <?= $comment['username']; ?>
                </td>
                <td>
                    <?= $comment['text']; ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>