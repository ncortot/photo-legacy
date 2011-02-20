all: htdocs/.htaccess config/gallery.conf

htdocs/.htaccess:
	cp htdocs/.htaccess.in htdocs/.htaccess

config/gallery.conf:
	cp config/gallery.ini config/gallery.conf

