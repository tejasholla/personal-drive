### PersonalDrive

A Laravel/react app to host and share your own files on S3. A personal alternative to googledrive.

> Or I can target it towards  sharing personal/social photos and videos with friends and family.  
> OR I can think of it as a backup drive. But then it needs versioning and encryption.

On the backend, let's stick with basic password auth only for now. we'll also explore how to get rate limiting setup. that amount of auth should be fine. make sure csrf and the like are working/. add a remember me token

But sharing to work it will need to have sso and proper user authentication system ?



Next let's have a admin page to setup S3. a way to authenticate into S3. authentication for sharing. etc . This can also monitor S3 for usage and costs ?
Next we'll need backend functions to interface with S3 - list all folders/files, download a file. Handle errors.


Then all the file manager functions on react and corresponding Laravel controllers.
Add pagination + preview photos, videos.
Upload feature + drag and drop in future.. Handle large files upload
Next we'll need Thumbnail generation .

make sure to have kick ass logging and error handling in laravel

s3 large file download/upload handling 

add file search to the application - laravel + react


## Grouped Features

### React
-[x] File manager functions (list files, preview photos/videos)
-[ ] Pagination for file lists
-[ ] Upload feature (drag-and-drop support in future)
-[x] File search UI

### S3 Specific
-[ ] S3 authentication and setup (admin page)
-[ ] Monitor S3 usage and costs
-[ ] Interface with S3 (list folders/files, download files, handle S3-specific errors)
-[ ] Large file handling for uploads/downloads

### Laravel
-[ ] Basic password authentication (with remember me token, CSRF protection)
-[ ] Rate limiting setup
-[ ] Logging and error handling
-[ ] File manager backend (controllers for file operations)
-[ ] SSO and advanced authentication for sharing (future)
-[x] File search logic (integrated with React)



### Features 
- list multiple buckets
- Ability to index all files of a bucket
- Search a bucket recursively
- browse a bucket in a fact react file manager

##### Features over filbrowser

select all, easier multiselect
no shift,ctrl to select multiple
autoplay video
sortable type column
instant sort

##### Features < filbrowser

no drag drop upload
no drag drop in file manager
text file viewer
pdf viewer
text file creator and editor
