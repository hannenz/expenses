PROJECT_NAME:=expenses
PROJECT_DIR:=$(CURDIR)

# Programs and tool
SASS_COMPILER:=gsassc
JS_COMPRESSOR:=yui-compressor
SVG_OPTIMIZER:=svgo
SVGO_OPTIONS:=--enable=removeStyleElement --disable=cleanupIDs --disable=convertStyleToAttrs
SVG_MERGER:=svgmerge
SVGMERGE_OPTIONS:=
PNG_OPTIMIZER:=pngcrush
PNG_OPTIMIZER_OPTIONS:=-q
JPG_OPTIMIZER:=jpegtran
JPG_OPTIMIZER_OPTIONS:=-copy none -optimize

# Paths
JS_SRC_DIR:=src/js
JS_DEST_DIR:=webroot/js
JS_DEST_FILE:=$(JS_DEST_DIR)/main.min.js

CSS_SRC_DIR:=src/css
CSS_DEST_DIR:=webroot/css
CSS_DEST_FILE:=$(CSS_DEST_DIR)/main.css

IMG_SRC_DIR:=src/img
IMG_DEST_DIR:=webroot/img

ICON_SRC_DIR:=src/icons
ICON_DEST_DIR:=webroot/img
ICON_DEST_FILE:=$(ICON_DEST_DIR)/icons.svg

BACKUP_DIR:=/tmp

# Specify directories under src/ to be copied directly (No trailing slashes!!)
COPYDIRS = js/vendor css/vendor fonts

# Database (Development)
DB_NAME:=expenses
DB_USER:=root
DB_PASSWORD:=pebble

#------------------
# DEPLOY: Needs SSH 
#------------------

# Deploy Staging
# STAGING_HOST:=wind
# STAGING_DIR:=/var/www/html/expenses

# Deploy Production
PRODUCTION_HOST:=wind
PRODUCTION_DIR:=/var/www/html/expenses/
PRODUCTION_DB_NAME:=expenses
PRODUCTION_DB_USER:=root
PRODUCTION_DB_PASSWORD:=pebble

# Environment
DATE:=$(shell date +%F-%H%M)
HOSTNAME:=$(shell hostname)

PNG_SRC_FILES:= $(shell find $(IMG_SRC_DIR) -type f -iname '*.png')
PNG_DEST_FILES:=$(patsubst $(IMG_SRC_DIR)/%.png, $(IMG_DEST_DIR)/%.png, $(PNG_SRC_FILES))
JPG_SRC_FILES:= $(shell find $(IMG_SRC_DIR) -type f -iname '*.jpg')
JPG_DEST_FILES:=$(patsubst $(IMG_SRC_DIR)/%.jpg, $(IMG_DEST_DIR)/%.jpg, $(JPG_SRC_FILES))
GIF_SRC_FILES:= $(shell find $(IMG_SRC_DIR) -type f -iname '*.gif')
GIF_DEST_FILES:=$(patsubst $(IMG_SRC_DIR)/%.gif, $(IMG_DEST_DIR)/%.gif, $(GIF_SRC_FILES))
SVG_SRC_FILES:= $(shell find $(IMG_SRC_DIR) -type f -iname '*.svg')
SVG_DEST_FILES:=$(patsubst $(IMG_SRC_DIR)/%.svg, $(IMG_DEST_DIR)/%.svg, $(SVG_SRC_FILES))
ICON_SRC_FILES:=$(shell find $(ICON_SRC_DIR) -type f -iname '*.svg')
ICON_OPT_FILES:=$(addsuffix o, $(ICON_SRC_FILES))

CSS_SRC_FILES:=$(shell find $(CSS_SRC_DIR) -type f -iname '*.scss')
JS_SRC_FILES:=$(shell ls $(JS_SRC_DIR)/*.js)

# Function for colored output
define colorecho
	@tput setaf $1
	@echo $2
	@tput sgr0
endef


# Main build target
# all: css js icons svg png jpg gif $(COPYDIRS)
all: css js $(COPYDIRS)

# -----------------------
# CSS / SASS
# -----------------------

css: $(CSS_DEST_DIR)/main.css
	$(call colorecho, 3, $(shell du -BK $@))

$(CSS_DEST_FILE): $(CSS_SRC_DIR)/main.scss $(CSS_SRC_FILES)
	@mkdir -p $(CSS_DEST_DIR)
	$(call colorecho, 3, "Compiling $@");
	@-$(SASS_COMPILER) -g -t compressed -o $@ $< \
			&& ([ $$? -eq 0 ] && (tput setaf 2; echo ✔ Compilation succeeded; tput sgr0))\
			|| (tput setaf 1; tput bold; echo ✖ Compilation failed; tput  sgr0)


# -----------------------
# JAVASCRIPT
# -----------------------

js: $(JS_DEST_DIR)/main.min.js
	$(call colorecho, 2, $(shell du -BK $@))

$(JS_DEST_FILE): $(JS_SRC_FILES)
	$(call colorecho, 3, "Compiling $@")
	@mkdir -p $(JS_DEST_DIR)
	@-cat $^ | $(JS_COMPRESSOR) -v --type js -o $@ \
			&& ([ $$? -eq 0 ] && (tput setaf 2; echo ✔ Compilation succeeded; tput sgr0))\
			|| (tput setaf 1; tput bold; echo ✖ Compilation failed; tput  sgr0)


#--------------------------
# ICONS
# -------------------------

icons:		$(ICON_OPT_FILES) $(ICON_DEST_FILE)
	$(call colorecho, 2, $(shell du -BK $(ICON_DEST_FILE)))

$(ICON_DEST_FILE): 	$(ICON_OPT_FILES)
	@mkdir -p img
	$(call colorecho, 3, "Compiling icons file: $@")
	@-$(SVG_MERGER) $(SVGMERGE_OPTIONS) -o $@ $^ \
			&& ([ $$? -eq 0 ] && (tput setaf 2; echo ✔ Compilation succeeded; tput sgr0))\
			|| (tput setaf 1; tput bold; echo ✖ Compilation failed; tput  sgr0)
	@-sed '1d' -i $@
	
%.svgo: %.svg
	$(call colorecho, 3, "Optimizing SVG file: $@")
	$(SVG_OPTIMIZER) $(SVGO_OPTIONS) -i $^ -o $@



# -----------------------
#  SVG
#  ----------------------

images:		svg jpg png gif

svg:	$(SVG_DEST_FILES)

$(IMG_DEST_DIR)/%.svg : $(IMG_SRC_DIR)/%.svg
	mkdir -p $(shell dirname $@)
	$(call colorecho, 3, "Optimizing file: $@")
	$(call colorecho, 7, $(shell du -BK $<))
	@$(SVG_OPTIMIZER) $(SVGO_OPTIONS) -i $< -o $@ && (tput setaf 2; du -BK $@ ; tput sgr0)


# -----------------------
# IMAGES (PNG, JPG, GIF) 
#  ----------------------

png: 	$(PNG_DEST_FILES)

$(IMG_DEST_DIR)/%.png : $(IMG_SRC_DIR)/%.png
	mkdir -p $(shell dirname $@)
	$(call colorecho, 3, "Optimizing file: $@")
	$(call colorecho, 7, $(shell du -BK $<))
	@$(PNG_OPTIMIZER) $(PNG_OPTIMIZER_OPTIONS) $< $@ && (tput setaf 2; du -BK $@ ; tput sgr0)

jpg: 	$(JPG_DEST_FILES)

$(IMG_DEST_DIR)/%.jpg : $(IMG_SRC_DIR)/%.jpg
	mkdir -p $(shell dirname $@)
	$(call colorecho, 3, "Optimizing file: $@")
	$(call colorecho, 7, $(shell du -BK $<))
	@$(JPG_OPTIMIZER) $(JPG_OPTIMIZER_OPTIONS) -outfile $@ $< && (tput setaf 2; du -BK $@ ; tput sgr0)

gif: 	$(GIF_DEST_FILES)

$(IMG_DEST_DIR)/%.gif : $(IMG_SRC_DIR)/%.gif
	mkdir -p $(shell dirname $@)
	$(call colorecho, 3, "Copying file: $@")
	@cp -a $< $@ 


favicon.ico:	$(IMG_SRC_DIR)/favicon.png
	convert $< $@

# -----------------------
# Directly copy files
# ----------------------

copy: $(COPYDIRS)
$(COPYDIRS):
	@echo "Copying dir: $@"
	@mkdir -p $@
	@rsync -rupE src/$@/ webroot/$@



# -----------------------
# BACKUP & DEPLOY
# -----------------------

backup:
	$(call colorecho, 7, "Creating Backup at $(BACKUP_DIR)")
	@mkdir -p $(BACKUP_DIR)/$(PROJECT_NAME)/$(DATE)
	$(call colorecho, 3, "Creating SQL Dump")
	@mysqldump -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME) | gzip > $(BACKUP_DIR)/$(PROJECT_NAME)/$(DATE)/$(DB_NAME).$(HOSTNAME).$(DATE).sql.gz
	$(call colorecho, 3, "Copying files")
	@rsync -a ./ $(BACKUP_DIR)/$(PROJECT_NAME)/$(DATE)/ --exclude-from=".backup-excludes"
	$(call colorecho, 3, "Creating Archive")
	@tar cfz $(BACKUP_DIR)/$(PROJECT_NAME).$(HOSTNAME).$(DATE).tar.gz $(BACKUP_DIR)/$(PROJECT_NAME)/$(DATE)/
	$(call colorecho, 3, "Cleaning up")
	@rm -rf $(BACKUP_DIR)/$(PROJECT_NAME).$(HOSTNAME).$(DATE)

deploy:
	$(call colorecho, 7, "Deploying to $(PRODUCTION_HOST) [production]")
	@ssh $(PRODUCTION_HOST) "mkdir -p $(PRODUCTION_DIR)"
	@rsync -ave ssh $(PROJECT_DIR)/ $(PRODUCTION_HOST):$(PRODUCTION_DIR)/ --exclude-from=".deploy-excludes"

fetch-from-production:
	ssh $(PRODUCTION_HOST) "mysqldump -u $(PRODUCTION_DB_USER) -p$(PRODUCTION_DB_PASSWORD) $(PRODUCTION_DB_NAME)" | mysql -u $(DB_USER) -p$(DB_PASSWORD) $(DB_NAME)

deploy-staging:
	$(call colorecho, 7, "Deploying to $(STAGING_HOST) [staging]")
	@ssh $(STAGING_HOST) "mkdir -p $(STAGING_DIR)"
	@rsync -ave ssh $(PROJECT_DIR)/ $(STAGING_HOST):$(STAGING_DIR)/ --exclude-from=".deploy-excludes"

# Fetch database and media files from staging
# fetch-from-staging:
# 	$(call colorecho, 7, "Fecthing from $(STAGING_HOST)")
# 	@ssh $(STAGING_HOST) "mysqldump -u root -pahrah4uX agenturhalma" | mysql -u root -ppebble agenturhalma
# 	@rsync -ave ssh $(STAGING_HOST):$(STAGING_DIR)/media/ $(PROJECT_DIR)/media/

clean:
	@echo "Cleaning up"
	@rm -Rf $(JS_DEST_DIR)
	@rm -Rf $(CSS_DEST_DIR)
	@rm -Rf $(ICON_OPT_FILES)
	@rm -Rf $(PNG_DEST_FILES)
	@rm -Rf $(JPEG_DEST_FILES)
	@rm -Rf $(IMG_DEST_DIR)

rebuild: clean all


.PHONY: css js png jpg svg gif client clean rebuild copy $(COPYDIRS) deploy deploy-staging backup icons images
