<p align="center">
  <img src="public/img/logo.png" alt="Logo">
 <h2 align="center">PERSONAL DRIVE</h2>
 <p align="center">A self hosted alternative to google drive and dropbox. 
</p>

## Demo:
https://demo.personaldrive.xyz/

## Requirements:
- A server running PHP with sqlite, PHP composer, nodejs, npm.
- Sudo access for setting permissions.
- Webserver user name (if not www-data)
- Files for upload
- Friends to share files with

### Installation:
#### Use from Docker Hub 
Personal Drive is hosted on docker hub.  
Make a new directory, cd into it, then:
```bash
mkdir storage
touch database.sqlite
docker run \
    -v storage:/var/www/html/personal-drive-storage-folder \
    -v database.sqlite:/database/database.sqlite \
    -p 8080:80 \
    docker.io/personaldrive/personaldrive
```
**`docker run`** → Starts a new container.  
**`-v storage:/var/www/html/personal-drive-storage-folder`** → Maps the storage folder to a path inside the container (for storage).  
**`-v database.sqlite:/database/database.sqlite`** → Maps the SQLite database file to the container.  
**`-p 8080:80`** → Exposes the container’s port 80 to your computer’s port 8080.  
Now open http://localhost:8080  

#### Regular Installation
Clone the repo and runs the guided setup script.
```bash
 git clone git@github.com:gyaaniguy/personal-drive.git
 cd personal-drive
 chmod +x setup.sh
 ./setup.sh
```

Ensure PHP and the webserver allow large uploads.   
**It is vital that the 'storage, bootstrap/cache and database' folders are writable for the webserver** . The setup script attempts to set these permissions.

 
Next :
- Set up your webserver to point your site to personal-drive/public
- Open the site and follow the on-screen wizard to create an admin account and set up the storage folder.



### Configuration:
- Storage folder can be changed from 'Settings'
- Increasing upload limits is crucial and depends on your web server app - apache, nginx, caddy. Detailed instructions are present on the 'settings' page after app installation.
- Increasing PHP and PHP-FPM (if used) memory limits is also crucial.
- The following folders require write permissions:
```bash
storage
bootstrap/cache
database
```
The setup script adjusts permissions and ownership if provided with root access

### Features:  

- Share files:
  - Password protection
  - Set expiration
  - Set custom URL
  - A sharing control panel, to pause and delete existing shares
- Media player. Slideshow:
  - Play and view images and videos
  - Preview text and pdf files
  - Keyboard shortcuts available during slideshow . Left, right, escape
- Files are indexed
- Dynamically generated thumbnails
- Upload multiple files or entire folders recursively
- Select one or all files in a folder
- Download, delete, share selected files
- Two layouts: list view and tile view
- Fast sort, even for thousands of files
- Breadcrumb navigation

### Development:
Built with Laravel 11 and React. Inertia.js connects React components to the Laravel backend. Uses SQLite as the database.
PHP code follows psr-12 standard

### Forgot password: 
Admin Password cannot be changed. This is done to reduce attack surface. If you forget your password: 
- reinstall the app OR delete the `database/database.sqlite` file -> This will remove all 'shares'
- Manually edit the password in the above database file

### Known Issues:
- Files overwrite existing ones without rename options

### Todo:
- Drag and Drop Upload
- Rename Functionality

### Screenshots:

<p align="center">
  <img src="public/img/share-screen.png" alt="Logo">
 <h2 align="center">PERSONAL DRIVE</h2>
 <p align="center">A self hosted alternative to google drive and dropbox. 
</p>
