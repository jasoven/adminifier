// reflect the current sort method
(function (data) {
    if (!data['data-sort']) return;
    var split = data['data-sort'].split('');
    var sort = split[0], order = split[1];
    var char = order == '+' ? 'caret-up' : 'caret-down';
    var th = $$('th[data-sort="' + sort + '"] a')[0];
    if (th) th.innerHTML +=
        ' <i class="fa fa-' + char +
        '" style="padding-left: 3px; width: 1em;"></i>';
})(adminifier.currentData);
