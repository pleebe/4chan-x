Captcha.v2 =
  lifetime: 2 * $.MINUTE

  init: ->
    return if d.cookie.indexOf('pass_enabled=1') >= 0
    return unless (@isEnabled = !!$ '#g-recaptcha, #captchaContainerAlt, #captcha-forced-noscript')

    lastActive = null
    lastStyle = null
    setInterval ->
      if (d.activeElement.nodeName is 'IFRAME' or lastActive?.nodeName is 'IFRAME') and d.activeElement isnt lastActive
        c.log 'scrollY', window.scrollY
        c.log 'activeElement', d.activeElement?.nodeName, d.activeElement?.src?.match(/^[^?]*/)[0]
      lastActive = d.activeElement
      bubble = $ 'iframe[src^="https://www.google.com/recaptcha/api2/frame"]'
      style = bubble?.parentNode.parentNode.getAttribute 'style'
      if style isnt lastStyle
        c.log 'scrollY', window.scrollY
        c.log style
        c.log JSON.stringify ([k, v] for k, v of bubble?.getBoundingClientRect())
      lastStyle = style
      return
    , 1

    if (@noscript = Conf['Force Noscript Captcha'] or not Main.jsEnabled)
      $.addClass QR.nodes.el, 'noscript-captcha'

    @captchas = []
    $.get 'captchas', [], ({captchas}) ->
      QR.captcha.sync captchas
    $.sync 'captchas', @sync.bind @

    root = $.el 'div', className: 'captcha-root'
    $.extend root, <%= html(
      '<div class="captcha-counter"><a href="javascript:;"></a></div>'
    ) %>
    counter   = $ '.captcha-counter > a', root
    @nodes = {root, counter}
    @count()
    $.addClass QR.nodes.el, 'has-captcha', 'captcha-v2'
    $.after QR.nodes.com.parentNode, root

    $.on counter, 'click', @toggle.bind @
    $.on counter, 'keydown', (e) =>
      return unless Keybinds.keyCode(e) is 'Space'
      @toggle()
      e.preventDefault()
      e.stopPropagation()
    $.on window, 'captcha:success', =>
      # XXX Greasemonkey 1.x workaround to gain access to GM_* functions.
      $.queueTask => @save false
    new MutationObserver(@watchBubbles.bind @).observe d.body,
      childList: true

  timeouts: {}
  postsCount: 0

  noscriptURL: ->
    url = 'https://www.google.com/recaptcha/api/fallback?k=<%= meta.recaptchaKey %>'
    if (lang = Conf['captchaLanguage'].trim())
      url += "&hl=#{encodeURIComponent lang}"
    url

  needed: ->
    captchaCount = @captchas.length
    captchaCount++ if QR.req
    @postsCount = QR.posts.length
    @postsCount = 0 if @postsCount is 1 and !Conf['Auto-load captcha'] and !QR.posts[0].com and !QR.posts[0].file
    captchaCount < @postsCount

  onNewPost: ->
    @setup()

  onPostChange: ->
    @setup() if @postsCount is 0
    @postsCount = 0 if QR.posts.length is 1 and !Conf['Auto-load captcha'] and !QR.posts[0].com and !QR.posts[0].file

  toggle: ->
    if @nodes.container and !@timeouts.destroy
      @destroy()
    else
      @setup true, true

  setup: (focus, force) ->
    return unless @isEnabled and (@needed() or force)
    @nodes.counter.focus() if focus
    if @timeouts.destroy
      clearTimeout @timeouts.destroy
      delete @timeouts.destroy
      return @reload()

    if @nodes.container
      # XXX https://bugzilla.mozilla.org/show_bug.cgi?id=1226835
      $.queueTask =>
        if @nodes.container and d.activeElement is @nodes.counter and (iframe = $ 'iframe', @nodes.container)
          iframe.focus()
          QR.focus() # Event handler not fired in Firefox
      return

    @nodes.container = $.el 'div', className: 'captcha-container'
    $.prepend @nodes.root, @nodes.container
    new MutationObserver(@afterSetup.bind @).observe @nodes.container,
      childList: true
      subtree: true

    if @noscript
      @setupNoscript()
    else
      @setupJS()

  setupNoscript: ->
    iframe = $.el 'iframe',
      id: 'qr-captcha-iframe'
      src: @noscriptURL()
    div = $.el 'div'
    textarea = $.el 'textarea'
    $.add div, textarea
    $.add @nodes.container, [iframe, div]

  setupJS: ->
    $.globalEval '''
      (function() {
        function render() {
          var container = document.querySelector("#qr .captcha-container");
          container.dataset.widgetID = window.grecaptcha.render(container, {
            sitekey: '<%= meta.recaptchaKey %>',
            theme: document.documentElement.classList.contains('tomorrow') ? 'dark' : 'light',
            callback: function(response) {
              window.dispatchEvent(new CustomEvent("captcha:success", {detail: response}));
            }
          });
        }
        if (window.grecaptcha) {
          render();
        } else {
          var cbNative = window.onRecaptchaLoaded;
          window.onRecaptchaLoaded = function() {
            render();
            cbNative();
          }
        }
      })();
    '''

  afterSetup: (mutations) ->
    for mutation in mutations
      for node in mutation.addedNodes
        @setupIFrame   iframe   if (iframe   = $.x './descendant-or-self::iframe',   node)
        @setupTextArea textarea if (textarea = $.x './descendant-or-self::textarea', node)
    return

  setupIFrame: (iframe) ->
    c.log 'setupIFrame', iframe
    Captcha.replace.iframe iframe
    $.addClass QR.nodes.el, 'captcha-open'
    if QR.nodes.el.getBoundingClientRect().bottom > doc.clientHeight
      QR.nodes.el.style.top    = null
      QR.nodes.el.style.bottom = '0px'
    iframe.focus() if d.activeElement is @nodes.counter
    # XXX Stop Recaptcha from changing focus from iframe -> body -> iframe on submit.
    $.global ->
      f = document.querySelector('#qr iframe')
      f.focus = f.blur = ->

  setupTextArea: (textarea) ->
    $.one textarea, 'input', => @save true

  destroy: ->
    return unless @isEnabled
    delete @timeouts.destroy
    $.rmClass QR.nodes.el, 'captcha-open'
    $.rm @nodes.container if @nodes.container
    delete @nodes.container
    # Clean up abandoned iframes.
    garbage = $.X '//iframe[starts-with(@src, "https://www.google.com/recaptcha/api2/frame")]/ancestor-or-self::*[parent::body]'
    i = 0
    while node = garbage.snapshotItem i++
      $.rm ins if (ins = node.nextSibling)?.nodeName is 'INS'
      $.rm node
    return

  sync: (captchas=[]) ->
    @captchas = captchas
    @clear()
    @count()

  getOne: ->
    @clear()
    if (captcha = @captchas.shift())
      $.set 'captchas', @captchas
      @count()
      captcha
    else
      null

  save: (pasted, token) ->
    $.forceSync 'captchas'
    @captchas.push
      response: token or $('textarea', @nodes.container).value
      timeout:  Date.now() + @lifetime
    @captchas.sort (a, b) -> a.timeout - b.timeout
    $.set 'captchas', @captchas
    @count()

    focus = d.activeElement?.nodeName is 'IFRAME' and /https?:\/\/www\.google\.com\/recaptcha\//.test(d.activeElement.src)
    if @needed()
      if focus
        if QR.cooldown.auto or Conf['Post on Captcha Completion']
          @nodes.counter.focus()
        else
          QR.nodes.status.focus()
      @reload()
    else
      if pasted
        @destroy()
      else
        @timeouts.destroy ?= setTimeout @destroy.bind(@), 3 * $.SECOND
      QR.nodes.status.focus() if focus

    QR.submit() if Conf['Post on Captcha Completion'] and !QR.cooldown.auto

  clear: ->
    return unless @captchas.length
    $.forceSync 'captchas'
    now = Date.now()
    for captcha, i in @captchas
      break if captcha.timeout > now
    return unless i
    @captchas = @captchas[i..]
    @count()
    $.set 'captchas', @captchas
    @setup(d.activeElement is QR.nodes.status)

  count: ->
    @nodes.counter.textContent = "Captchas: #{@captchas.length}"
    clearTimeout @timeouts.clear
    if @captchas.length
      @timeouts.clear = setTimeout @clear.bind(@), @captchas[0].timeout - Date.now()

  reload: ->
    if $ 'iframe[src^="https://www.google.com/recaptcha/api/fallback?"]', @nodes.container
      @destroy()
      @setup false, true
    else
      $.globalEval '''
        (function() {
          var container = document.querySelector("#qr .captcha-container");
          window.grecaptcha.reset(container.dataset.widgetID);
        })();
      '''

  watchBubbles: (mutations) ->
    for mutation in mutations
      for node in mutation.addedNodes
        if $ 'iframe[src^="https://www.google.com/recaptcha/api2/frame"]', node
          new MutationObserver(@fixBubble.bind(@, node)).observe node,
            attributes: true

  fixBubble: (node) ->
    {bottom, right, width} = node.getBoundingClientRect()
    if (
      getComputedStyle(node).visibility isnt 'hidden' and
      bottom > 0 and          # not deliberately offscreen
      right > doc.clientWidth # offscreen to right
    )
      qrLeft = QR.nodes.el.getBoundingClientRect().left
      newLeft = Math.max 0, qrLeft - width
      node.style.left = "#{newLeft}px"
