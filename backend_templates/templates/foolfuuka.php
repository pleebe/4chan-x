<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="generator" content="FoolFuuka 2.2.0">
    <title><?= $title ?></title>
    <link href="/" rel="index" title="<?= htmlentities($config['site_title']) ?>">

    <link rel="stylesheet" type="text/css" href="/assets/bootstrap.legacy.css">
    <link rel="stylesheet" type="text/css" href="/assets/font-awesome.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!--[if lt IE 8]>
    <link rel="stylesheet" type="text/css" href="/assets/font-awesome-ie7.css">
    <![endif]-->

    <link href="/assets/foolfuuka.css" rel="stylesheet" type="text/css">
    <link href="/assets/flags.css" rel="stylesheet" type="text/css">
    <link href="/assets/polflags2.css" rel="stylesheet" type="text/css">
    <link href="/assets/mobile.css" rel="stylesheet" type="text/css">
    <?php if ($skin !== 'default' && $skin !== 'midnight'): ?>
        <link href="/assets/<?= htmlentities($skin) ?>.css" rel="stylesheet" type="text/css">
    <?php endif; ?>
    <link rel="search" type="application/opensearchdescription+xml" title="<?= htmlentities($config['site_title']) ?>" href="/_/opensearch/">
</head>
<body class="theme_default <?= htmlentities($skin) ?> <?php if ($is_thread): echo 'is_thread'; else: echo ' is_index '; endif; ?> <?= ($is_board ? 'board_' . htmlentities($shortname) : '') ?>">
<div class="container-fluid">
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <ul class="nav">
                    <li class="dropdown">
                        <a href="/" id="brand" class="brand dropdown-toggle"
                           data-toggle="dropdown"><?= ($is_board ? $title : htmlentities($config['site_title'])) ?> <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="/">Index</a></li><li class="divider"></li>
                            <li class="nav-header">Archives</li>
                            <?php foreach ($config['boards'] as $board_shortname => $name) : ?>
                                <li><a href="/<?= htmlentities($board_shortname) ?>/">/<?= htmlentities($board_shortname) ?>/ - <?= htmlentities($name) ?></a></li>
                            <?php endforeach; ?>
                            <li class="divider"></li>
                            <li class="nav-header">Boards</li>
                            <?php foreach ($config['internal_boards'] as $board_shortname => $name) : ?>
                                <li><a href="/<?= htmlentities($board_shortname) ?>/">/<?= htmlentities($board_shortname) ?>/ - <?= htmlentities($name) ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>

                <ul class="nav"><?php if ($is_board): ?>
                    <?php if ($is_archive): ?>
                    <li>
                        <a href="https://boards.4chan.org/<?= htmlentities($shortname) ?>/" style="padding-right:4px;">4chan <i class="icon-share icon-white text-small"></i></a>
                    </li>
                    <?php endif; ?>
                    <li style="padding-right:0px;">
                        <a href="/<?= htmlentities($shortname) ?>/" style="padding-right:4px;">Index</a>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="padding-left:2px; padding-right:4px;">
                            <b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu" style="margin-left:-9px">
                            <li>
                                <a href="#">By Post <i class="icon-ok"></i>
                                </a>
                            </li>
                            <li>
                                <a href="#">By Thread </a>
                            </li>
                        </ul>
                    </li>
                    <li><a href="/<?= htmlentities($shortname) ?>/ghost/">Ghost</a></li>
                        <li><a href="/<?= htmlentities($shortname) ?>/#gallery">Gallery</a></li>
                        <li><a href="/<?= htmlentities($shortname) ?>/statistics/">Stats</a></li>
                    <?php endif; ?>
                </ul>
                <ul class="nav pull-right">
                    <form class="navbar-search" method="GET" action="/<?= htmlentities($shortname) ?>/search/">
                        <li><input name="text" value="" class="search-query" placeholder="Search through all the boards" type="text"></li>
                    </form>
                </ul>
            </div>
        </div>
    </div>
    <div role="main" id="main">
        <div class="search_box">
            <div class="advanced_search clearfix">
                <form method="GET" action="/<?= htmlentities($shortname) ?>/search/">
                    <div class="comment_wrap">
                        <input name="text" id="search_form_comment" value="" placeholder="Search through all the boards" type="text">        </div>

                    <div class="buttons clearfix">
                        <input class="btn btn-inverse" value="Search" name="submit_search" type="submit">
                        <input class="btn btn-inverse" value="Search on all boards" name="submit_search_global" type="submit">

                        <input class="btn btn-inverse pull-right" value="Clear" name="reset" data-function="clearSearch" type="reset">        </div>


                    <div class="column">
                        <div class="input-prepend"><label class="add-on" for="search_form_tnum">Thread No.</label><input name="tnum" id="search_form_tnum" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_subject">Subject</label><input name="subject" id="search_form_subject" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_username">Username</label><input name="username" id="search_form_username" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_tripcode">Tripcode</label><input name="tripcode" id="search_form_tripcode" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_email">Email</label><input name="email" id="search_form_email" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_uid">Unique ID</label><input name="uid" id="search_form_uid" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_filename">Filename</label><input name="filename" id="search_form_filename" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_image">Image Hash</label><input name="image" id="search_form_image" value="" placeholder="Drop your image here" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_width">Image Width</label><input name="width" id="search_form_width" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_height">Image Height</label><input name="height" id="search_form_height" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_tag">File Tag</label><input name="tag" id="search_form_tag" value="" placeholder="/f/ only" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_since4pass">Since4pass</label><input name="since4pass" id="search_form_since4pass" value="" placeholder="" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_country">Country</label><input name="country" id="search_form_country" value="" placeholder="/pol/ &amp; /sp/ only" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_troll_country">Troll Country</label><input name="troll_country" id="search_form_troll_country" value="" placeholder="/pol/ only" type="text"></div><div class="input-prepend"><label class="add-on" for="search_form_start">Date Start</label><input type="text" name="start" placeholder="YYYY-MM-DD" autocomplete="off" value=""></div><div class="input-prepend"><label class="add-on" for="search_form_end">Date End</label><input type="text" name="end" placeholder="YYYY-MM-DD" autocomplete="off" value=""></div>
                        <label><input data-function="searchhilight" class="searchhilight"  checked="checked"  type="checkbox"> Highlight Results</label>

                        <div class="radixes">
                            <div>
                                <div><h5>On these archives</h5>
                                    <button type="button" data-function="checkAll" class="btn btn-mini pull-right check" style="display:none">Check all</button>
                                    <button type="button" data-function="uncheckAll" class="btn btn-mini pull-right uncheck" style="display:block">Uncheck all</button>
                                </div>
                                <label><input type="checkbox" name="boards[]" value="adv" checked="checked"> /adv/</label><label><input type="checkbox" name="boards[]" value="f" checked="checked"> /f/</label><label><input type="checkbox" name="boards[]" value="hr" checked="checked"> /hr/</label><label><input type="checkbox" name="boards[]" value="o" checked="checked"> /o/</label><label><input type="checkbox" name="boards[]" value="pol" checked="checked"> /pol/</label><label><input type="checkbox" name="boards[]" value="s4s" checked="checked"> [s4s]</label><label><input type="checkbox" name="boards[]" value="sp" checked="checked"> /sp/</label><label><input type="checkbox" name="boards[]" value="tg" checked="checked"> /tg/</label><label><input type="checkbox" name="boards[]" value="trv" checked="checked"> /trv/</label><label><input type="checkbox" name="boards[]" value="tv" checked="checked"> /tv/</label><label><input type="checkbox" name="boards[]" value="x" checked="checked"> /x/</label>                                        </div>

                            <div style="clear:left; padding-top: 10px">
                            </div>
                        </div>

                        <div class="latest_searches">
                            <div>
                                <h5>Your latest searches</h5>
                                <button type="button" data-function="clearLatestSearches" class="btn btn-mini pull-right">Clear</button>
                            </div>
                            <ul>
                            </ul>
                        </div>
                    </div>
                    <div class="column checkboxes"><table class="table"><tbody>
                            <tr><td>Capcode</td><td>
                                    <label><input type="radio" name="capcode" value="" checked="checked">All</label>
                                    <label><input type="radio" name="capcode" value="user">Only User Posts</label>
                                    <label><input type="radio" name="capcode" value="ver">Only Verified Posts</label>
                                    <label><input type="radio" name="capcode" value="mod">Only Moderator Posts</label>
                                    <label><input type="radio" name="capcode" value="manager">Only Manager Posts</label>
                                    <label><input type="radio" name="capcode" value="admin">Only Admin Posts</label>
                                    <label><input type="radio" name="capcode" value="dev">Only Developer Posts</label>
                                    <label><input type="radio" name="capcode" value="founder">Only Founder Posts</label>
                                </td></tr>
                            <tr><td>Show Posts</td><td>
                                    <label><input type="radio" name="filter" value="" checked="checked">All</label>
                                    <label><input type="radio" name="filter" value="text">Only With Images</label>
                                    <label><input type="radio" name="filter" value="image">Only Without Images</label>
                                    <label><input type="radio" name="filter" value="spoiler">Only Spoiler Images</label>
                                    <label><input type="radio" name="filter" value="not-spoiler">Only Non-Spoiler Images</label>
                                </td></tr>
                            <tr><td>Deleted Posts</td><td>
                                    <label><input type="radio" name="deleted" value="" checked="checked">All</label>
                                    <label><input type="radio" name="deleted" value="deleted">Only Deleted Posts</label>
                                    <label><input type="radio" name="deleted" value="not-deleted">Only Non-Deleted Posts</label>
                                </td></tr>
                            <tr><td>Ghost Posts</td><td>
                                    <label><input type="radio" name="ghost" value="" checked="checked">All</label>
                                    <label><input type="radio" name="ghost" value="only">Only Ghost Posts</label>
                                    <label><input type="radio" name="ghost" value="none">Only Non-Ghost Posts</label>
                                </td></tr>
                            <tr><td>Post Type</td><td>
                                    <label><input type="radio" name="type" value="" checked="checked">All</label>
                                    <label><input type="radio" name="type" value="sticky">Only Sticky Threads</label>
                                    <label><input type="radio" name="type" value="op">Only Opening Posts</label>
                                    <label><input type="radio" name="type" value="posts">Only Reply Posts</label>
                                </td></tr>
                            <tr><td>Results</td><td>
                                    <label><input type="radio" name="results" value="" checked="checked">All</label>
                                    <label><input type="radio" name="results" value="thread">Grouped By Threads</label>
                                </td></tr>
                            <tr><td>Order</td><td>
                                    <label><input type="radio" name="order" value="" checked="checked">Latest Posts First</label>
                                    <label><input type="radio" name="order" value="asc">Oldest Posts First</label>
                                </td></tr>
                            </tbody></table></div>
                </form>
            </div>
        </div>
        <noscript>
        <div class="alert" style="margin:15%;">
            <h4 class="alert-heading">Error!</h4>
            This site does not work without JavaScript </div>
        </noscript>

        <?php if ($not_found) : ?>
            <div class="alert" style="margin:15%;">
                <h4 class="alert-heading">Error!</h4>
                Page not found. You can use the search if you were looking for something! </div>
        <?php else: ?>
        <?php if ($is_board) : ?>
        <div class="board">
        <?php if ($thread_num) : ?>
            <div id="t<?= htmlentities($thread_num) ?>" class="thread">
                <div class="postContainer opContainer" id="pc<?= htmlentities($thread_num) ?>">
                    <div id="p<?= htmlentities($thread_num) ?>" class="post op">
                    </div>
                </div>
            </div>
        <?php endif; ?>
        </div>
        <?php if ($is_thread): ?>
            <hr>
            <div class="navLinks navLinksBot desktop">
            </div>
            <hr class="desktop">
        <?php endif; ?>
        <?php if (!$is_thread && !$is_gallery): ?>
                <div class="pagelist desktop">
                    <div class="prev"><span>Previous</span></div>
                    <div class="pages">
                    </div>
                    <div class="next">
                        <form class="pageSwitcherForm" action="2">
                            <input type="submit" value="Next" accesskey="x">
                        </form>
                    </div>
                    <div class="pages cataloglink">
                    </div>
                    <div class="pages cataloglink">
                    </div>
                </div>
        <?php endif; ?>
        <?php else: ?>
            <nav class="index_nav clearfix">
                <h1></h1>
                <ul class="pull-left clearfix">
                    <li><h2>Archives</h2></li>
                    <li>
                        <?php foreach ($config['boards'] as $board_shortname => $name) : ?>
                        <ul>
                            <li><h3><a href="<?= htmlentities($board_shortname) ?>/">/<?= htmlentities($board_shortname) ?>/ <span class="help"><?= htmlentities($name) ?></span></a></h3></li>
                        </ul>
                        <?php endforeach; ?>
                    </li>
                </ul>
                <ul class="pull-left clearfix">
                    <li><h2>Boards</h2></li>
                    <li>
                        <ul>
                            <?php foreach ($config['internal_boards'] as $board_shortname => $name) : ?>
                            <li><h3><a href="<?= htmlentities($board_shortname) ?>/">/<?= htmlentities($board_shortname) ?>/ <span class="help"><?= htmlentities($name) ?></span></a></h3></li>
                            <?php endforeach; ?>
                        </ul>
                    </li>
                </ul>
        <ul class="pull-left clearfix">
            <li><h2>Articles</h2></li>
            <li>
                <ul>
        <?php foreach ($config['articles'] as $url => $name) : ?>
        <li><h3><a href="<?= $url ?>"><?= $name ?></a></h3></li>
        <?php endforeach; ?>
                </ul>
            </li>
            </nav>
        <?php endif; ?>
        <?php endif; ?>
    </div> <!-- end of #main -->
    <div id="push"></div>
</div>
<footer id="footer">
    <a href="https://github.com/pleebe/4plebs-x" target="_blank">FoolFuuka skin</a> - <a href="http://github.com/eksopl/asagi" target="_blank">Asagi Fetcher</a>
    <div class="pull-right">
        <div class="btn-group dropup pull-right">
            <a href="#" class="btn btn-inverse btn-mini dropup-toggle" data-toggle="dropdown">
                Change Theme <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <?php foreach ($config['themes'] as $t => $name) : ?>
                <li>
                    <a href="/_/theme/<?= htmlentities($t) ?>/"><?= htmlentities($name) ?>
                        <?php if ($theme === explode('-', $t)[0] && $skin === explode('-', $t)[1]): ?>
                            <i class="icon-ok"></i>
                        <?php endif; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</footer>
<script src="/<?= $config['javascript_file'] ?>"></script>
</body>
</html>
