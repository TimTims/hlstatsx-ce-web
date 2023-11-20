# Changelog

### 20/11/2023 - Git Commit 99
* Fixed table layout on some pages
* Ensured barcharts in tables are centered horizontally
* Added "align=middle" to draw table class to allow vertical alignment
* Fix sidebar not showing full length
* Removed Google Maps code in "game.php" (Will implement a new system with an FOSS alternative)
* Make sure all admin tasks open up in new page, rather than in the table
* Fixed general settings by removing stylesheet selection (Will re-add option to allow for customisation)
* Redesigned all "general" admin tasks

### 03/11/2023 - Git Commit 98
* Fix missing div tags in game.php when daily awards are disabled
* Fix missing header and div tags in certain conditions
* Fix missing div tag by adding it to footer.php (temporary fix, will add to individual pages eventually)
* Changed layout of admin page*
    *NOTE: The in-page admin settings are currently broken. They need a code change to make them function properly which I will do at a later date.

### 02/11/2023 - Git Commit 97
* Fix page spacing on "Check Version" and "Duplicate Game Settings" pages
* Fix inconsistent capitalisation on "Player Award" page
* Redesigned "Clean Up Statistics" page
* Redesigned "Full or Partial Reset" page
* Redesigned "Reset DB Collations" page
* Redesigned "Optimize Database" page
* Add SSL option in config and default it to true for logout page (temporary fix)
* Fixed image path in config

### 31/10/2023 - Git Commit 95 & 96
**96**
* Fix missing quotation mark on "Check Update" page
* Added a "View Changelog" button on "Check Update" page
* Redesigned "Admin Events" page (still WIP)
* Redesigned "Duplicate Game Settings" page

**95**
* Update version.json to link to dev edition
* Begin fixing up admin page
* Removed temporary "Edit Player" button in profiles for nicer permanent one
* Redesign "HLstatsX: CE Daemon Control" page
* Redesign "Edit Player / Clan Details" page
* Fix search page form
* Redesigned "Award Details" page
* Fix up "Check Version" page
* Added "Version Status" to "Check Update" page

### 24/10/2023 - Git Commit 94
* Fix up some tables in pages
* Fix top nav item alignment when logged in
* Redesigned login page
* Added some custom CSS for text colours
* Enabled popovers universally (will create JS script to enable when popover is on page for performance)
* Added custom function for alignment in tables (not completed or utilised yet)
* Made search page responsive

### 15/10/2023 - Git Commit 93
* Fix up player profile page (still need to fix layout and possibly add tabs/accordions)
* Add TODO list
* Add "Edit Player" button if logged in as admin on player profile
* Fix profile card height to look better