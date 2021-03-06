<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(1, "mmi_about", $Language->MenuPhrase("1", "MenuText"), "aboutlist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}about'), FALSE);
$RootMenu->AddMenuItem(2, "mmi_admin_users", $Language->MenuPhrase("2", "MenuText"), "admin_userslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}admin_users'), FALSE);
$RootMenu->AddMenuItem(3, "mmi_blog", $Language->MenuPhrase("3", "MenuText"), "bloglist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}blog'), FALSE);
$RootMenu->AddMenuItem(4, "mmi_branches", $Language->MenuPhrase("4", "MenuText"), "brancheslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}branches'), FALSE);
$RootMenu->AddMenuItem(5, "mmi_co_emails", $Language->MenuPhrase("5", "MenuText"), "co_emailslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}co_emails'), FALSE);
$RootMenu->AddMenuItem(6, "mmi_email_list", $Language->MenuPhrase("6", "MenuText"), "email_listlist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}email_list'), FALSE);
$RootMenu->AddMenuItem(7, "mmi_news", $Language->MenuPhrase("7", "MenuText"), "newslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}news'), FALSE);
$RootMenu->AddMenuItem(8, "mmi_offers", $Language->MenuPhrase("8", "MenuText"), "offerslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}offers'), FALSE);
$RootMenu->AddMenuItem(9, "mmi_phones", $Language->MenuPhrase("9", "MenuText"), "phoneslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}phones'), FALSE);
$RootMenu->AddMenuItem(10, "mmi_photo_albums", $Language->MenuPhrase("10", "MenuText"), "photo_albumslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}photo_albums'), FALSE);
$RootMenu->AddMenuItem(11, "mmi_photos", $Language->MenuPhrase("11", "MenuText"), "photoslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}photos'), FALSE);
$RootMenu->AddMenuItem(12, "mmi_projects", $Language->MenuPhrase("12", "MenuText"), "projectslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}projects'), FALSE);
$RootMenu->AddMenuItem(13, "mmi_sectors", $Language->MenuPhrase("13", "MenuText"), "sectorslist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}sectors'), FALSE);
$RootMenu->AddMenuItem(14, "mmi_slide", $Language->MenuPhrase("14", "MenuText"), "slidelist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}slide'), FALSE);
$RootMenu->AddMenuItem(15, "mmi_social", $Language->MenuPhrase("15", "MenuText"), "sociallist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}social'), FALSE);
$RootMenu->AddMenuItem(16, "mmi_study", $Language->MenuPhrase("16", "MenuText"), "studylist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}study'), FALSE);
$RootMenu->AddMenuItem(17, "mmi_top_adv", $Language->MenuPhrase("17", "MenuText"), "top_advlist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}top_adv'), FALSE);
$RootMenu->AddMenuItem(18, "mmi_videos_library", $Language->MenuPhrase("18", "MenuText"), "videos_librarylist.php", -1, "", AllowListMenu('{2740043E-02B0-4E15-B10B-02037850EEC7}videos_library'), FALSE);
$RootMenu->AddMenuItem(-1, "mmi_logout", $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, "mmi_login", $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
<!-- End Main Menu -->
