<?

require_once('functions/session.php');
require_once('functions/wikiclient.php');

$pages = $W->page_list('a+')->pages;
echo "Hi {$_SESSION['username']}<br />";
echo '<a href="logout.php">Logout</a><br />';

echo '<table>';
foreach ($pages as $page) {
    echo
    '<tr><td>' .
    '<a href="edit-page.php?page='.$page->file.'">' .
    $page->title .
    '</td></tr>';
}
echo '</table>';

?>