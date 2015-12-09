# drilling.gr

This is the git repository of the http://drilling.gr website. 

Upload is done via rsync over ssh with:

    rsync  \
        --exclude 'assets/bs/css/bootstrap-theme*' \
        --exclude 'assets/bs/css/bootstrap.css' \
        --exclude 'assets/bs/js/bootstrap.js' \
        --delete-after --delete-excluded \
        -avz -e 'ssh' \
        ./ user@server:/path/to/drilling.gr/

Have fun!

