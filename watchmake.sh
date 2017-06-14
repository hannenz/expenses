#!/bin/sh

url="expenses.localhost"

# Set-Up browser-sync
browser-sync start --no-notify --proxy "${url}" --files "webroot/css/**/*.css" "webroot/js/**/*.js" &

# watch & make
excludes="\.swp$"
inotifywait -mr -e modify --format "%w%f" --excludei "${excludes}" ./src/ | while read file 
do
	ext=${file##*.}
	echo "File ${file} has been modified [${ext}]"

	case "${ext}" in
		scss|js|svg)
			make
			;;
		*)
			;;
	esac
done

