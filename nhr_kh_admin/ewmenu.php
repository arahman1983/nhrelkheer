<!-- Begin Main Menu -->
<?php $RootMenu = new cMenu(EW_MENUBAR_ID) ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mi_about", $Language->MenuPhrase("1", "MenuText"), "aboutlist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}about'), FALSE);
$RootMenu->AddMenuItem(17, "mi_top_adv", $Language->MenuPhrase("17", "MenuText"), "top_advlist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}top_adv'), FALSE);
$RootMenu->AddMenuItem(14, "mi_slide", $Language->MenuPhrase("14", "MenuText"), "slidelist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}slide'), FALSE);
$RootMenu->AddMenuItem(13, "mi_sectors", $Language->MenuPhrase("13", "MenuText"), "sectorslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}sectors'), FALSE);
$RootMenu->AddMenuItem(12, "mi_projects", $Language->MenuPhrase("12", "MenuText"), "projectslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}projects'), FALSE);
$RootMenu->AddMenuItem(8, "mi_offers", $Language->MenuPhrase("8", "MenuText"), "offerslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}offers'), FALSE);
$RootMenu->AddMenuItem(7, "mi_news", $Language->MenuPhrase("7", "MenuText"), "newslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}news'), FALSE);

$RootMenu->AddMenuItem(10, "mi_photo_albums", $Language->MenuPhrase("10", "MenuText"), "photo_albumslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}photo_albums'), FALSE);
$RootMenu->AddMenuItem(11, "mi_photos", $Language->MenuPhrase("11", "MenuText"), "photoslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}photos'), FALSE);
$RootMenu->AddMenuItem(18, "mi_videos_library", $Language->MenuPhrase("18", "MenuText"), "videos_librarylist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}videos_library'), FALSE);

$RootMenu->AddMenuItem(16, "mi_study", $Language->MenuPhrase("16", "MenuText"), "studylist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}study'), FALSE);
$RootMenu->AddMenuItem(3, "mi_blog", $Language->MenuPhrase("3", "MenuText"), "bloglist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}blog'), FALSE);

$RootMenu->AddMenuItem(4, "mi_branches", $Language->MenuPhrase("4", "MenuText"), "brancheslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}branches'), FALSE);
$RootMenu->AddMenuItem(5, "mi_co_emails", $Language->MenuPhrase("5", "MenuText"), "co_emailslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}co_emails'), FALSE);
$RootMenu->AddMenuItem(9, "mi_phones", $Language->MenuPhrase("9", "MenuText"), "phoneslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}phones'), FALSE);
$RootMenu->AddMenuItem(15, "mi_social", $Language->MenuPhrase("15", "MenuText"), "sociallist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}social'), FALSE);

$RootMenu->AddMenuItem(6, "mi_email_list", $Language->MenuPhrase("6", "MenuText"), "email_listlist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}email_list'), FALSE);

$RootMenu->AddMenuItem(2, "mi_admin_users", $Language->MenuPhrase("2", "MenuText"), "admin_userslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}admin_users'), FALSE);

$RootMenu->AddMenuItem(-1, "mi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
