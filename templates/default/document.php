<?php
/* @var $data \CodeDocs\TemplateData */
?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?php echo $data->name ?> | Documentation</title>

    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="<?php echo $data->baseUrl . 'assets/css/bootstrap.min.css' ?>">
    <link rel="stylesheet" href="<?php echo $data->baseUrl . 'assets/css/main.css' ?>">
    <link rel="stylesheet" href="<?php echo $data->baseUrl . 'assets/css/highlight.github.min.css' ?>">
</head>
<body>


<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
    <div class="container">
        <div class="navbar-header">
            <a href="<?php echo $data->baseUrl . 'index.html' ?>" class="navbar-brand"><?php echo $data->name ?> - Documentation</a>
        </div>
        <?php if (isset($data->additional['headerLinks'])) { ?>
            <nav role="navigation">
                <ul class="nav navbar-nav">
                    <?php foreach ($data->additional['headerLinks'] as $label => $href) { ?>
                        <li>
                            <a href="<?php echo $href ?>"><?php echo $label ?></a>
                        </li>
                    <?php } ?>
                </ul>
            </nav>
        <?php } ?>
    </div>
</header>

<div class="container">
    <div class="row">
        <div class="col-xs-3" id="leftCol">

            <?php
            $breadcrumb = [];
            ?>

            <ul class="nav" id="sidebar">
                <?php foreach ($data->items as $item) { ?>
                    <?php if ($item->children) { ?>
                        <li>
                            <h5><?php echo $item->label ?></h5>
                            <ul>
                            <?php foreach ($item->children as $subItem) { ?>
                                <li>
                                    <?php
                                    if ($subItem == $data->currentItem) {
                                        $breadcrumb = [
                                            $item->label,
                                            $subItem->label
                                        ];
                                        ?>
                                        <strong><?php echo $subItem->label ?></strong>
                                        <?php
                                    } else {
                                        ?>
                                        <a href="<?php echo $data->baseUrl . $subItem->relUrl ?>"><?php echo $subItem->label ?></a>
                                        <?php
                                    }
                                    ?>
                                </li>
                            <?php } ?>
                            </ul>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>

        </div>
        <div class="col-xs-9">

            <?php if($breadcrumb) { ?>
                <ol class="breadcrumb">
                    <?php foreach ($breadcrumb as $item) { ?>
                        <li><?php echo $item ?></li>
                    <?php } ?>
                </ol>
            <?php } ?>

            <?php if ($data->currentItem->relUrl == 'index.html') { ?>
                <div class="jumbotron">
                    <h1><?php echo $data->name ?></h1>
                    <p><?php echo $data->description ?></p>
                </div>
            <?php } ?>

            <div class="markdown">
                <?php echo $data->content ?>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo $data->baseUrl . 'assets/js/jquery.min.js' ?>"></script>
<script src="<?php echo $data->baseUrl . 'assets/js/bootstrap.min.js' ?>"></script>
<script src="<?php echo $data->baseUrl . 'assets/js/highlight.min.js' ?>"></script>
<script>
    $('#sidebar').affix({
        offset: {
            top: 0,
            bottom: 0
        }
    });

    $('pre code').each(function(i, block) {
        hljs.highlightBlock(block);
    });

    $('abbr').tooltip();
    $('a').tooltip();
    $('img').tooltip();
</script>

</body>
</html>
