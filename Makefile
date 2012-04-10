all: htdocs/.htaccess config/gallery.conf

htdocs/.htaccess:
	cp htdocs/.htaccess.in htdocs/.htaccess

config/gallery.conf:
	cp config/gallery.conf.in config/gallery.conf
