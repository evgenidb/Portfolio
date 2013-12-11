<section>
    <form method="post" action=<?= $data['send_form_to']; ?> >
        <div>
            <label for="book_title">Заглавие:</label>
            <input type="text" id="book_title" name="book_title" />
        </div>
        <div>
            <label for="book_authors">Автори:</label>
            <select id="book_authors" name="book_authors[]" multiple>
                <?php
                foreach ($data['authors'] as $id => $name) {
                    ?>
                    <option value="<?= $id ?>"><?= $name ?></option>
                    <?php
                }
                ?>
            </select>
        </div>
        <div>
            <input type="submit" value="Добави" />
        </div>
    </form>
</section>