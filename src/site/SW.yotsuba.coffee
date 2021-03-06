SW.yotsuba =
  isOPContainerThread: false

  urls:
    thread:     ({boardID, threadID}) -> "#{location.protocol}//archive.4plebs.org/#{boardID}/thread/#{threadID}"
    threadJSON: ({boardID, threadID}) -> "#{location.protocol}//archive.4plebs.org/#{boardID}/thread/#{threadID}.json"

  selectors:
    board:         '.board'
    thread:        '.thread'
    threadDivider: '.board > hr'
    summary:       '.summary'
    postContainer: '.postContainer'
    sideArrows:    '.sideArrows'
    post:          '.post'
    infoRoot:      '.postInfo'
    info:
      subject:   '.subject'
      name:      '.name'
      email:     '.useremail'
      tripcode:  '.postertrip'
      uniqueIDRoot: '.posteruid'
      uniqueID:  '.posteruid > .hand'
      capcode:   '.capcode.hand'
      pass:      '.n-pu'
      flag:      '.flag, .countryFlag'
      date:      '.dateTime'
      nameBlock: '.nameBlock'
      quote:     '.postNum > a:nth-of-type(2)'
      reply:     '.replylink'
    icons:
      isSticky:   '.stickyIcon'
      isClosed:   '.closedIcon'
      isArchived: '.archivedIcon'
    file:
      text:  '.file > :first-child'
      link:  '.fileText > a'
      thumb: 'a.fileThumb > [data-md5]'
    foolfuuka_file:
      text:  '.post_file_metadata'
      link:  'a.fileText'
      thumb: 'a.fileThumb > [data-md5]'
      controls: '.post_file_controls'
    comment:   '.postMessage'
    spoiler:   's'
    quotelink: ':not(pre) > .quotelink' # XXX https://github.com/4chan/4chan-JS/issues/77: 4chan currently creates quote links inside [code] tags; ignore them
    boardList: '#boardNavDesktop > .boardList'
    styleSheet: 'link[title=switch]'

  xpath:
    thread:        'div[contains(concat(" ",@class," ")," thread ")]'
    postContainer: 'div[contains(@class,"postContainer")]'

  regexp:
    quotelink:
      ///
        ^https?://(?:archive|test)\.4plebs\.org/+
        ([^/]+) # boardID
        /+thread/+
        (\d+)   # threadID
        (?:[/?][^#]*)?
        (?:#p
        (\d+(?:_|,\d+)?)   # postID
        )?
        $
      ///
    quotelinkHTML:
      /<a [^>]*\bhref="(?:(?:\/\/(?:archive|test)\.4plebs\.org)?\/([^\/]+)\/thread\/)?(\d+)?(?:#p(\d+))?"/g

  bgColoredEl: ->
    $.el 'div', className: 'reply'

  isThisPageLegit: ->
    # not 404 error page or similar.
    location.hostname in ['test.4plebs.org', 'archive.4plebs.org'] and
    d.doctype and
    !$('link[href*="favicon-status.ico"]', d.head) and
    d.title not in ['4plebs - Temporarily Offline', '4plebs - Error', '504 Gateway Time-out', 'MathJax Equation Source']

  is404: ->
    # XXX Sometimes threads don't 404 but are left over as stubs containing one garbage reply post.
    d.title in ['4plebs - Temporarily Offline', '4plebs - 404 Not Found'] or (g.VIEW is 'thread' and $('.board') and not $('.opContainer'))

  isIncomplete: ->
    return g.VIEW in ['index', 'thread'] and not $('.board + *')

  isAuxiliaryPage: ->
    location.hostname not in ['test.4plebs.org', 'archive.4plebs.org']

  scriptData: ->
    for script in $$ 'script:not([src])', d.head
      return script.textContent if /\bcooldowns *=/.test script.textContent
    ''

  parseThreadMetadata: (thread) ->
    scriptData = @scriptData()
    thread.postLimit = /\bbumplimit *= *1\b/.test scriptData
    thread.fileLimit = /\bimagelimit *= *1\b/.test scriptData
    thread.ipCount   = if (m = scriptData.match /\bunique_ips *= *(\d+)\b/) then +m[1]

    if g.BOARD.ID is 'f' and thread.OP and thread.OP.file
      {file} = thread.OP
      $.ajax "#{location.protocol}//archive.4plebs.org/f/thread/#{thread}.json",
        timeout: $.MINUTE
        onloadend: ->
          if @response
            file.text.dataset.md5 = file.MD5 = @response.posts[0].md5

  parseNodes: (post, nodes) ->
    # Add CSS classes to sticky/closed icons on /f/ to match other boards.
    if post.boardID is 'f'
      for type in ['Sticky', 'Closed'] when (icon = $ "img[alt=#{type}]", nodes.info)
        $.addClass icon, "#{type.toLowerCase()}Icon", 'retina'

  parseFile: (post, file) ->
    {text, link, thumb} = file
    if Build.getCookie('theme') is 'foolfuuka'
      return false if not (info = text.textContent.match /([\d.]+ [KMG]?B).*/)
    else
      return false if not (info = link.nextSibling?.textContent.match /\(([\d.]+ [KMG]?B).*\)/)
    $.extend file,
      name:       text.title or link.title or link.textContent
      size:       info[1]
      dimensions: info[0].match(/\d+x\d+/)?[0]
      tag:        info[0].match(/,[^,]*, ([a-z]+)\)/i)?[1]
      MD5:        text.dataset.md5
    if thumb
      $.extend file,
        thumbURL:  thumb.src
        MD5:       thumb.dataset.md5
        isSpoiler: $.hasClass thumb.parentNode, 'imgspoiler'
      if file.isSpoiler
        file.thumbURL = if (m = link.href.match /\d+(?=\.\w+$)/) then "#{location.protocol}//#{ImageHost.thumbHost()}/#{post.board}/#{m[0]}s.jpg"
    true

  cleanComment: (bq) ->
    if (abbr = $ '.abbr', bq) # 'Comment too long' or 'EXIF data available'
      for node in $$ '.abbr + br, .exif', bq
        $.rm node
      for i in [0...2]
        $.rm br if (br = abbr.previousSibling) and br.nodeName is 'BR'
      $.rm abbr

  cleanCommentDisplay: (bq) ->
    $.rm b if (b = $ 'b', bq) and /^Rolled /.test(b.textContent)
    $.rm $('.fortune', bq)

  insertTags: (bq) ->
    for node in $$ 's, .removed-spoiler', bq
      $.replace node, [$.tn('[spoiler]'), node.childNodes..., $.tn '[/spoiler]']
    for node in $$ '.prettyprint', bq
      $.replace node, [$.tn('[code]'), node.childNodes..., $.tn '[/code]']
    return

  hasCORS: (url) ->
    url.split('/')[...3].join('/') is location.protocol + '//archive.4plebs.org'
