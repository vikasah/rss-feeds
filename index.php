<!DOCTYPE html>

<html>

<head>
    <title>RSS App</title>
    <link rel="stylesheet" href="public/css/style.min.css" media="screen"/>
</head>

<body>

    <form id="rss">
        <div class="rss-feeds__flash"></div>
        <h1>RSS Feeds</h1>
        <div class="rss-input">
            <input type="text" id="link" name="link" placeholder="Add a link ..." />
            <input type="submit" id="add" name="add" value="Add link" />
        </div>
        <div class="rss-feeds__table"></div>
        <div class="rss-feeds"></div>
    </form>

    <script src="public/js/main-min.js"></script>

</body>

</html>
