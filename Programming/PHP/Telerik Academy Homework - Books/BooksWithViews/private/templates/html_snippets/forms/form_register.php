<section>
    <form method="post" action=<?= $data['send_form_to']; ?> >
        <div>
            <label for="user_username">Потребителско име:</label>
            <input type="text" id="user_username" name="user_username" />
        </div>
        <div>
            <label for="user_password">Парола:</label>
            <?php
            if (IS_DEBUG_MODE) {
                ?>
                <input type="text" id="user_password" name="user_password" />
                <?php
            } else {
                ?>
                <input type="password" id="user_password" name="user_password" />
                <?php
            }
            ?>
        </div>
        <div>
            <input type="submit" value="Регистрирай се" />
        </div>
    </form>
</section>