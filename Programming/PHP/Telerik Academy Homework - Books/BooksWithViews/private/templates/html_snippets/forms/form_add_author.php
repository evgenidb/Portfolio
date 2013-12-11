<section>
    <form method="post" action=<?= $data['send_form_to']; ?> >
        <div>
            <label for="author_name">Име:</label>
            <input type="text" id="author_name" name="author_name" />
        </div>
        <div>
            <input type="submit" value="Добави" />
        </div>
    </form>
</section>