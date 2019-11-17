<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="robots" content="noarchive">
    <meta name="description" content="<?= $config['title'] ?>">
    <meta name="referrer" content="origin">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <?php if ($skin === 'red'): ?>
        <link rel="stylesheet" href="/assets/yotsubared.css">
    <?php else: ?>
        <link rel="stylesheet" href="/assets/yotsubablue.css">
    <?php endif; ?>
    <?php if ($skin === 'green'): ?>
        <link rel="stylesheet" href="/assets/yotsubagreen.css">
    <?php endif; ?>
    <link rel="shortcut icon" href="//archive.4plebs.org/favicon.ico">
    <title><?= $config['title'] ?></title>
</head>
<body class="is_index <?= ($is_board ? 'board_' . htmlentities($shortname) : '') ?>">
<?php if ($is_board) : ?>
    <span id="id_css"></span>
    <div id="boardNavDesktop" class="desktop">
    <span class="boardList">[
        <?php foreach ($config['boards'] as $shortname => $name) : ?>
            <a href="/<?= $shortname ?>/" title="<?= $name ?>"><?= $shortname ?></a> /
        <?php endforeach; ?>] </span>
        <span id="navtopright">[<a href="javascript:void(0);" id="settingsWindowLink">Settings</a>] [<a
                href="/search" title="Search">Search</a>] [<a href="/" target="_top">Home</a>]</span>
    </div>
    <div class="boardBanner">
        <div id="bannerCnt" class="title desktop" data-src="1.jpg"></div>
        <div class="boardTitle"><?= $config['title'] ?></div>
    </div>
    <hr class="abovePostForm">
    <div style='position:relative'></div>

    <hr class="aboveMidAd">
    <div class="middlead center">
    </div>
    <hr>
    <div class="globalMessage hideMobile" id="globalMessage" data-utc=""></div>
    <div class="adg-rects desktop">
        <hr>
        <div class="adg adp-228" data-rc="107704" id="rcjsload_top"></div>
    </div>
    <div id="ctrl-top" class="desktop">
        <hr>
        <input type="text" id="search-box" placeholder="Search OPs&hellip;"> [<a href="./catalog">Catalog</a>] [<a
            href="./archive">Archive</a>]
    </div>
    <hr>
    <form name="delform" id="delform" method="post">
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
            <div class="navLinks navLinksBot desktop">[<a href="/<?= htmlentities($shortname) ?>/"
                                                          accesskey="<?= htmlentities($shortname) ?>">Return</a>]
                [<a href="/<?= htmlentities($shortname) ?>/catalog">Catalog</a>] [<a href="#top">Top</a>]
            </div>
            <hr class="desktop">
        <?php endif; ?>
        <div class="bottomCtrl desktop">
        <span class="deleteform">

        </span>
            <span class="stylechanger">Style:

            <select onchange="this.options[this.selectedIndex].value && (window.location = '/_/theme/'+this.options[this.selectedIndex].value+'/');" id="styleSelector">
                <?php foreach ($config['themes'] as $t => $name) : ?>
                    <option value="<?= htmlentities($t) ?>"
                        <?php if ($theme === explode('-', $t)[0] && $skin === explode('-', $t)[1]): ?> selected<?php endif; ?>>
                        <?= htmlentities($name) ?></option>
                <?php endforeach; ?>
            </select>
    </span></div>

    </form>
    <?php if (!$is_thread): ?>
        <div class="pagelist desktop">
            <div class="prev"><span>Previous</span></div>
            <div class="pages">
                [<strong><a href="">1</a></strong>]
                [<a href="2">2</a>]
                [<a href="3">3</a>]
                [<a href="4">4</a>]
                [<a href="5">5</a>]
                [<a href="6">6</a>]
                [<a href="7">7</a>]
                [<a href="8">8</a>]
                [<a href="9">9</a>]
                [<a href="10">10</a>]
            </div>
            <div class="next">
                <form class="pageSwitcherForm" action="2">
                    <input type="submit" value="Next" accesskey="x">
                </form>
            </div>
            <div class="pages cataloglink">
                <a href="./catalog">Catalog</a>
            </div>
            <div class="pages cataloglink">
                <a href="./archive">Archive</a>
            </div>
        </div>
    <?php endif; ?>
    <div id="absbot" class="absBotText">
        <span class="absBotDisclaimer"></span>
        <div id="footer-links">
        </div>
    </div>
    <div id="bottom"></div>
<?php else: ?>
    <h3><?= htmlentities($config['site_title']) ?></h3>
    <h4>Archives:</h4>
    <?php foreach ($config['boards'] as $shortname => $name) : ?>
        <h5><a href="/<?= $shortname ?>/" title="<?= $name ?>">/<?= $shortname ?>/ - <?= $name ?></a></h5>
    <?php endforeach; ?>
    <h4>Boards:</h4>
    <?php foreach ($config['internal_boards'] as $shortname => $name) : ?>
        <h5><a href="/<?= $shortname ?>/" title="<?= $name ?>">/<?= $shortname ?>/ - <?= $name ?></a></h5>
    <?php endforeach; ?>
    <div class="bottomCtrl desktop">
        <span class="deleteform">

        </span>
        <span class="stylechanger">Style:

            <select onchange="this.options[this.selectedIndex].value && (window.location = '/_/theme/'+this.options[this.selectedIndex].value+'/');" id="styleSelector">
                <?php foreach ($config['themes'] as $t => $name) : ?>
                    <option value="<?= htmlentities($t) ?>"
                        <?php if ($theme === explode('-', $t)[0] && $skin === explode('-', $t)[1]): ?> selected<?php endif; ?>>
                        <?= htmlentities($name) ?></option>
                <?php endforeach; ?>
            </select>
    </span></div>
<?php endif; ?>
<script src="/<?= $config['javascript_file'] ?>"></script>
</body>
</html>