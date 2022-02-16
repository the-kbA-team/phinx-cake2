#!/usr/bin/env sh

set -e

# display usage information
show_usage() {
    echo "Wrapper script to use Phinx (https://phinx.org) with CakePHP 2.x."
    echo
    echo "Option"
    echo
    echo "  -c, --configuration=CONFIGURATION"
    echo
    echo "cannot be used with this script, this script uses a predefined"
    echo "configuration file"
    echo
    echo "Call Phinx directly if you want to use a custom configuration file"
    echo
    echo "Command"
    echo
    echo "  init"
    echo
    echo "cannot be used with this script."
    echo
    echo "Call Phinx directly if you want to use this command"
    echo
    echo "Now the output for phinx help is displayed:"
    echo
}

##
# Canonicalize by following every symlink of the given name recursively
# @param string 1 The file path to canonicalize.
##
canonicalize() {
    NAME="$1"
    if [ -f "$NAME" ]
    then
        DIR=$(dirname -- "$NAME")
        NAME=$(cd -P "$DIR" > /dev/null && pwd -P)/$(basename -- "$NAME")
    fi
    while [ -h "$NAME" ]; do
        DIR=$(dirname -- "$NAME")
        SYM=$(readlink "$NAME")
        NAME=$(cd "$DIR" > /dev/null && cd "$(dirname -- "$SYM")" > /dev/null && pwd)/$(basename -- "$SYM")
    done
    echo "$NAME"
}

SELF=$(canonicalize "$0")
PARENT_DIR=$(dirname "$(dirname -- "${SELF}")")
VENDOR_BIN_DIR=$(dirname "$(dirname -- "$PARENT_DIR")")/bin
PHINX_CFG_FILE="$PARENT_DIR"/config/phinx.php

for i in "$@"; do
    case $i in
        -c|--configuration=*)
            echo "You have to call phinx directly if you want to use a custom configuration"
            exit 2
        ;;
    esac
done

if [ -z "$1" ]
then
    show_usage
    "$VENDOR_BIN_DIR"/phinx help
else
    case $1 in
        init)
            echo "You have to call phinx directly if you want to use the init command"
            exit 2
        ;;
        completion|help|list)
            "$VENDOR_BIN_DIR"/phinx "$@"
        ;;
        *)
            "$VENDOR_BIN_DIR"/phinx "$@" -c "$PHINX_CFG_FILE"
        ;;
    esac
fi
