<?php

require_once 'class/Message.php';
require_once 'class/GuestBook.php';
$error = null;
$success = false;
$guestbook = new GuestBook(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'messages');
if (isset($_POST['username'], $_POST['message'])) {
    $message = new Message($_POST['username'], $_POST['message']);
    if ($message->isValid()) {
        $guestbook->addMessage($message);
        $success = true;
        $_POST = [];
    } else {
        $error = $message->getErrors();
    }
}
$messages = $guestbook->getMessages();
$title = "Livre d'Or";
require 'elements/header.php';
require 'elements/navbar.php';
?>

<div class="container">
    <h1>Livre d'Or</h1>
    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger">
            Formulaire invalide !
        </div>
    <?php endif; ?>

    <?php if ($success) : ?>
        <div class="alert alert-success">
            Merci pour votre message !
        </div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="form-group">
            <input value="<?= htmlentities($_POST['username'] ?? '') ?>" type="text" name="username" placeholder="Votre pseudo" class="form-control <?= isset($error['username']) ? 'is-invalid' : '' ?>">
            <?php if (isset($error['username'])) : ?>
                <div class="invalid-feeback"><?= $error['username'] ?></div>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <textarea name="message" placeholder="Votre message" class="form-control <?= isset($error['message']) ? 'is-invalid' : '' ?>"><?= htmlentities($_POST['message'] ?? '') ?></textarea>
            <?php if (isset($error['message'])) : ?>
                <div class="invalid-feeback"><?= $error['message'] ?></div>
            <?php endif; ?>
        </div>

        <button class="btn btn-primary mt-3 mb-4">Envoyer</button>
    </form>

    <div class="d-flex flex-column mb3">
        <?php if (!empty($messages)) : ?>

            <h2 class="mb-4">Vos messages : </h2>

            <?php foreach ($messages as $message) : ?>
                <?= $message->toHTML() ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>

<?php require 'elements/footer.php'; ?>