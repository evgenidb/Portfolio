<section>
    <form method="post" action=<?= $data['send_form_to']; ?> >
        <div>
            <label for="comment_text">Коментар:</label>
            <input type="text" id="comment_text" name="comment_text" />
        </div>
        <div class="hidden">
            <input type="hidden" name="book_id" value="<?= $data['book']['book_id']; ?>" />
        </div>
        <div>
            <input type="submit" value="Добави" />
        </div>
    </form>
</section>