<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">WWIIS Challenge</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav">
                <a class="nav-item nav-link active" href="index.php?p=search">Search Data<span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="index.php?p=import">Create Quote</a>
            </div>
        </div>
    </nav>
</header>

<!-- Pages content comes here-->
<div class="container-fluid">

    <?php
        $pages_dir = 'pages';

        if (!empty($_GET['p'])) {
            $pages = scandir($pages_dir, 0);
            unset($pages[0], $pages[1]);
            $p = $_GET['p'];

            if (in_array($p.'.php', $pages)) {
                include($pages_dir.'/'.$p.'.php');
            } else {
                echo 'Sorry, page not found.';
            }
        } else {
          include($pages_dir.'/search.php');
        }
    ?>

</div><!-- container -->