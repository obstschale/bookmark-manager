(function (window, document, href, bt_url, bt_base) {
    var encURI = window.encodeURIComponent,
        form = document.createElement('form'),
        head = document.getElementsByTagName('head')[0],
        target = '_bookmark_this_app',
        canPost = true,
        windowWidth, windowHeight, selection,
        metas, links, content, images, iframes, img;

    if (!bt_url) {
        return;
    }

    if (href.match(/^https?:/)) {
        bt_url += '&u=' + encURI(href);
        if (href.match(/^https:/) && bt_url.match(/^http:/)) {
            canPost = false;
        }
    } else {
        top.location.href = bt_url;
        return;
    }

    if (window.getSelection) {
        selection = window.getSelection() + '';
    } else if (document.getSelection) {
        selection = document.getSelection() + '';
    } else if (document.selection) {
        selection = document.selection.createRange().text || '';
    }

    bt_url += '&base=' + bt_base;

    bt_url += '&buster=' + ( new Date().getTime() );


    if (document.title) {
        bt_url += '&t=' + encURI(document.title.substr(0, 256));
    }

    if (selection) {
        bt_url += '&s=' + encURI(selection.substr(0, 512));
    }


    windowWidth = 800;
    windowHeight = 520;

    if (!canPost) {
        window.open(bt_url, target, 'location,resizable,scrollbars,width=' + windowWidth + ',height=' + windowHeight);
        return;
    }

    function add(name, value) {
        if (typeof value === 'undefined') {
            return;
        }

        var input = document.createElement('input');

        input.name = name;
        input.value = value;
        input.type = 'hidden';

        form.appendChild(input);
    }

    metas = head.getElementsByTagName('meta') || [];

    console.log(metas);
    for (var m = 0; m < metas.length; m++) {
        if (m > 200) {
            break;
        }

        var q = metas[m],
            q_name = q.getAttribute('name'),
            q_prop = q.getAttribute('property'),
            q_cont = q.getAttribute('content');

        if (q_cont) {
            if (q_name) {
                add('_meta[' + q_name + ']', q_cont);
            } else if (q_prop) {
                add('_meta[' + q_prop + ']', q_cont);
            }
        }
    }

    links = head.getElementsByTagName('link') || [];

    for (var y = 0; y < links.length; y++) {
        if (y >= 50) {
            break;
        }

        var g = links[y],
            g_rel = g.getAttribute('rel');

        if (g_rel === 'canonical' || g_rel === 'icon' || g_rel === 'shortlink') {
            add('_links[' + g_rel + ']', g.getAttribute('href'));
        }
    }

    if (document.body.getElementsByClassName) {
        content = document.body.getElementsByClassName('hfeed')[0];
    }

    content = document.getElementById('content') || content || document.body;
    images = content.getElementsByTagName('img') || [];

    for (var n = 0; n < images.length; n++) {
        if (n >= 100) {
            break;
        }

        img = images[n];

        // If we know the image width and/or height, check them now and drop the "uninteresting" images.
        if (img.src.indexOf('avatar') > -1 || img.className.indexOf('avatar') > -1 ||
            ( img.width && img.width < 256 ) || ( img.height && img.height < 128 )) {

            continue;
        }

        add('_images[]', img.src);
    }

    iframes = document.body.getElementsByTagName('iframe') || [];

    for (var p = 0; p < iframes.length; p++) {
        if (p >= 50) {
            break;
        }

        add('_embeds[]', iframes[p].src);
    }

    if (document.title) {
        add('t', document.title);
    }

    if (selection) {
        add('s', selection);
    }

    form.setAttribute('method', 'POST');
    form.setAttribute('action', bt_url);
    form.setAttribute('target', target);
    form.setAttribute('style', 'display: block;');

    window.open('about:blank', target, 'location,resizable,scrollbars,width=' + windowWidth + ',height=' + windowHeight);

    document.body.appendChild(form);
    form.submit();
})(window, document, top.location.href, window.bt_url, window.bt_base);
