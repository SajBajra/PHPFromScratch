<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact form</title>
</head>
<body>
    <h1>Contact form</h1>

    <form method="post" action="08-contact-submit.php">
        <div>
            <label>
                Name (required):
                <input type="text" name="name">
            </label>
        </div>

        <div>
            <label>
                Email (required):
                <input type="email" name="email">
            </label>
        </div>

        <div>
            <label>
                Message (required):
                <textarea name="message" rows="4" cols="40"></textarea>
            </label>
        </div>

        <button type="submit">Send message</button>
    </form>
</body>
</html>

