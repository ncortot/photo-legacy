all: htdocs/.htaccess config/gallery.conf htdocs/small htdocs/medium

htdocs/.htaccess:
	cp htdocs/.htaccess.in htdocs/.htaccess

config/gallery.conf:
	cp config/gallery.ini config/gallery.conf

htdocs/small:
	ln -s ../spool/images_small htdocs/small

htdocs/medium:
	ln -s ../spool/images_medium htdocs/medium
