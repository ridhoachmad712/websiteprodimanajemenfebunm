document.querySelectorAll('[data-local-collection]').forEach(function (collection) {
    var search = collection.querySelector('[data-collection-search]');
    var category = collection.querySelector('[data-collection-category]');
    var items = Array.from(collection.querySelectorAll('[data-collection-item]'));
    var update = function () {
        var query = search.value.trim().toLocaleLowerCase('id');
        var count = 0;
        items.forEach(function (item) {
            item.hidden = !(item.textContent.toLocaleLowerCase('id').includes(query) && (!category.value || item.dataset.category === category.value));
            if (!item.hidden) count++;
        });
        collection.querySelector('[data-collection-count]').textContent = count + ' dari ' + items.length + ' hasil';
        collection.querySelector('[data-collection-empty]').hidden = count > 0;
        collection.querySelectorAll('[data-collection-group]').forEach(function (group) {
            group.hidden = !Array.from(group.querySelectorAll('[data-collection-item]')).some(function (item) { return !item.hidden; });
        });
    };
    search.addEventListener('input', update);
    category.addEventListener('change', update);
    collection.querySelector('[data-collection-reset]').addEventListener('click', function () {
        search.value = ''; category.value = ''; update(); search.focus();
    });
    update();
});

document.addEventListener('DOMContentLoaded', function () {
    var menu = document.getElementById('navbar-menu');
    var search = document.getElementById('siteSearchPanel');
    var components = window.tabler || window.bootstrap;
    if (!components?.Collapse) return;
    search?.addEventListener('show.bs.collapse', function () {
        if (menu?.classList.contains('show')) components.Collapse.getOrCreateInstance(menu).hide();
    });
    menu?.addEventListener('show.bs.collapse', function () {
        if (search?.classList.contains('show')) components.Collapse.getOrCreateInstance(search).hide();
    });
    search?.addEventListener('shown.bs.collapse', function () { document.getElementById('siteSearch').focus(); });
    document.addEventListener('click', function (event) {
        if (!search?.classList.contains('show')) return;
        if (search.contains(event.target) || event.target.closest('.site-search-toggle')) return;
        components.Collapse.getOrCreateInstance(search).hide();
    });
    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') return;
        [menu, search].forEach(function (panel) {
            if (!panel?.classList.contains('show')) return;
            components.Collapse.getOrCreateInstance(panel).hide();
            document.querySelector('[aria-controls="' + panel.id + '"]')?.focus();
        });
    });
});
