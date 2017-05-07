<?

if (!isset($list_type))
    $list_type = 'pages';

// find the sort method
$valid_types = array('a', 'c', 'u', 'm');
if ($list_type == 'images')
    array_push($valid_types, 'd');
$sort  = 'm';
$order = '-';
if (isset($_GET['sort'])) {
    $parts = str_split($_GET['sort']);
    if (in_array($parts[0], $valid_types))
        $sort = $parts[0];
    $order = $parts[1] == '+' ? '+' : '-';
}

require_once(__DIR__.'/../functions/utils.php');

// page or model
$pages = array();
switch ($list_type) {
    case 'pages':       $pages = $W->page_list($sort.$order)->pages;      break;
    case 'models':      $pages = $W->model_list($sort.$order)->models;    break;
    case 'images':      $pages = $W->image_list($sort.$order)->images;    break;
    case 'categories':  $pages = $W->cat_list($sort.$order)->categories;  break;
}

// if the current sort method is the same as the one passed,
// this returns the opposite direction for the same method.
// if the sort method is different, it returns descending.
function sort_method ($type) {
    global $sort, $order, $list_type;
    $prefix = "#!/$list_type?sort=";
    if ($type == $sort)
        return $order == '-' ? $prefix.$type.'%2B' : $prefix.$type.'-';
    return $prefix.$type.'-';
}

function link_to ($page) {
    global $list_type;
    $encoded = urlencode($page->file);
    switch ($list_type) {
        case 'pages':       return "edit-page?page=$encoded";
        case 'models':      return "edit-model?page=$encoded";
        case 'images':      return "edit-image?file=$encoded";
        case 'categories':  return "edit-category?cat=$encoded";
    }
}

?>

<meta
<? if ($list_type == 'models'): ?>
    data-nav="models"
    data-title="Models"
    data-icon="cube"
<? elseif ($list_type == 'categories'): ?>
    data-nav="categories"
    data-title="Categories"
    data-icon="list"
<? elseif ($list_type == 'images'): ?>
    data-nav="images"
    data-title="Images"
    data-icon="picture-o"
    data-buttons="List imageDisplaySwitch"
<? else: ?>
    data-nav="pages"
    data-title="Pages"
    data-icon="file-text"
<? endif; ?>
    data-scripts="page-list"
    data-styles="page-list"
    data-flags="no-margin search buttons"
    data-search="pageSearch"
    data-sort="<?= $sort.$order ?>"
/>

<table id="page-list">
<thead>
    <th class="checkbox"><input type="checkbox" value="0" /></th>
    <th class="title" data-sort="a">
        <a class="frame-click" href="<?= sort_method('a') ?>">
            <?= isset($title_column) ? $title_column : 'Title' ?>
        </a>
    </th>
    <? if ($list_type == 'images'): ?>
        <th class="dimensions info" data-sort="d">
            <a class="frame-click" href="<?= sort_method('d') ?>">Dimensions</a>
        </th>
    <? endif; ?>
    <th class="author info" data-sort="u">
        <a class="frame-click" href="<?= sort_method('u') ?>">Author</a>
    </th>
    <th class="created info" data-sort="c">
        <a class="frame-click" href="<?= sort_method('c') ?>">Created</a>
    </th>
    <th class="created info" data-sort="m">
        <a class="frame-click" href="<?= sort_method('m') ?>">Modified</a>
    </th>
</thead>
<tbody>
<? foreach ($pages as $page): ?>
    <tr>
        <td class="checkbox">
            <input type="checkbox" value="0" />
        </td>
        <td class="title"<?
            if ($list_type == 'images') {
                echo 'style="background: url(functions/image.php?file=';
                echo urlencode($page->file);
                echo '&height=30) right center no-repeat;"';
            }
        ?>>
            <a class="frame-click" href="#!/<?= link_to($page); ?>">
                <?=
                    isset($page->title)
                        && strlen(trim($page->title)) ?
                    $page->title    :
                    $page->file
                ?>
                <? if (isset($page->redirect) && $page->redirect): ?>
                    <span class="pageinfo">Redirect</span>
                <? endif; ?>
                <? if (isset($page->draft) && $page->draft): ?>
                    <span class="pageinfo">Draft</span>
                <? endif; ?>
                <? if (isset($page->generated) && $page->generated): ?>
                    <span class="pageinfo">Generated</span>
                <? endif; ?>
                <? if ($list_type == 'images' && !$page->width): ?>
                    <span class="pageinfo">Missing</span>
                <? endif; ?>
            </a>
        </td>
        <? if ($list_type == 'images'): ?>
            <td class="dimensions info">
                <? if ($page->width) echo $page->width.'x'.$page->height; ?>
            </td>
        <? endif; ?>
        <td class="author info">
            <?=
                isset($page->author) ?
                htmlspecialchars($page->author) : ''
            ?>
        </td>
        <td class="created info">
            <?=
                isset($page->created)           ?
                date('M j Y', $page->created)   : ''
            ?>
        </td>
        <td class="modified info">
            <?=
                isset($page->mod_unix)         ?
                date('M j Y', $page->mod_unix) : ''
            ?>
        </td>
    </tr>
<? endforeach; ?>

</tbody>
</table>
