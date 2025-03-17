# PERSONAL DRIVE

A self hosted alternative to google drive. For those who can setup their own server


## Requirements:
- PHP 8, node, npm, sqlite

### Installation:
- copy or clone repo
- composer install
- npm build
- setup server to point your site or  sub_domain to /public
- Open site. Follow the on-screen wizard to create admin account and setup storage folder

### Configuration:
- Storage folder can be changed from 'Settings'
- increasing upload limits is vital and will depend on the server type and web server app - apache, nginx. Detailed instructions are present on the 'settings' page after app installation.
- Increasing PHP memory limits is also important if you plan to upload large files.
- Please also make sure the server has write permissions to the storage folder


### Features:  

- Share files. Many features
  - password protect
  - set expiry 
  - set custom url
  - A sharing control panel, to pause and delete existing shares
- Media player. Slideshow:
  - Play and view images and videos
  - Preview text and pdf files
  - Keyboard shortcuts available during slideshow . Left, right, escape 
- Dynamically generated thumbnails
- Upload multiple files or folder at one go ! Folders are uploaded recursively
- Ability to Select one or all files in a folder
- Download, delete, share selected files.
- 2 layouts. list view and tile view 
- Fast sort, even for thousands of files
- Breadcrumb view

### Development:
Project has been developed in Laravel 11 and react. We use inertia.js to tie together react components with the laravel backend.
PHP code follows psr-12 standard


### Known Issues:

- thumbnails, don't show before reload after first upload
- Files are overwritten without giving an option to rename existing files

### Todo:
- Drag and Drag Upload
- Rename
